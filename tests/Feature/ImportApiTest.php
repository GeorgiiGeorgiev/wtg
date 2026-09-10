<?php

namespace Tests\Feature;

use App\Jobs\ProcessImport;
use App\Models\Import;
use App\Models\Offer;
use App\Models\Supplier;
use App\Services\ImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ImportApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_is_queued_once_and_processed(): void
    {
        Queue::fake();

        Supplier::factory()->create(['code' => 'supplier-a']);

        $payload = [
            'supplier' => 'supplier-a',
            'external_import_id' => 'import-1',
            'sent_at' => now()->toISOString(),
            'offers' => [$this->offerPayload(72500)],
        ];

        $response = $this->postJson('/api/imports', $payload)
            ->assertAccepted()
            ->assertJsonPath('data.status', Import::STATUS_PENDING);

        $importId = $response->json('data.id');

        Queue::assertPushed(ProcessImport::class, 1);

        (new ProcessImport($importId))->handle(app(ImportService::class));

        $this->postJson('/api/imports', $payload)
            ->assertAccepted()
            ->assertJsonPath('data.id', $importId)
            ->assertJsonPath('data.status', Import::STATUS_COMPLETED);

        Queue::assertPushed(ProcessImport::class, 1);
        $this->assertDatabaseCount('imports', 1);
        $this->assertDatabaseCount('offers', 1);

        $this->getJson("/api/imports/{$importId}")
            ->assertOk()
            ->assertJsonPath('data.status', Import::STATUS_COMPLETED)
            ->assertJsonPath('data.processed_offers', 1);
    }

    public function test_newer_import_updates_offer_and_older_import_cannot_overwrite_it(): void
    {
        $supplier = Supplier::factory()->create();
        $service = app(ImportService::class);

        $first = Import::factory()->for($supplier)->create([
            'sent_at' => now()->subDay(),
            'total_offers' => 1,
            'payload' => [$this->offerPayload(80000)],
        ]);
        $service->processNextBatch($first);

        $newer = Import::factory()->for($supplier)->create([
            'sent_at' => now(),
            'total_offers' => 1,
            'payload' => [$this->offerPayload(70000)],
        ]);
        $service->processNextBatch($newer);

        $older = Import::factory()->for($supplier)->create([
            'sent_at' => now()->subDays(2),
            'total_offers' => 1,
            'payload' => [$this->offerPayload(60000)],
        ]);
        $service->processNextBatch($older);

        $offer = Offer::where('external_id', 'offer-1')->firstOrFail();

        $this->assertSame(70000, $offer->price);
        $this->assertSame($newer->id, $offer->import_id);
    }

    private function offerPayload(int $price): array
    {
        return [
            'external_id' => 'offer-1',
            'property' => [
                'code' => 'BCN-0001',
                'name' => 'Barcelona Apartment',
                'city' => 'Barcelona',
            ],
            'check_in' => now()->addMonth()->toDateString(),
            'check_out' => now()->addMonth()->addDays(5)->toDateString(),
            'max_guests' => 4,
            'price' => $price,
            'currency' => 'EUR',
            'available_units' => 2,
            'expires_at' => now()->addWeek()->toISOString(),
        ];
    }
}
