<?php

namespace Tests\Feature;

use App\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_last_available_unit_can_only_be_reserved_once(): void
    {
        $offer = Offer::factory()->create([
            'available_units' => 1,
            'expires_at' => now()->addHour(),
        ]);

        $this->postJson("/api/offers/{$offer->id}/reservations", [
            'client_reference' => 'order-1',
            'customer_name' => 'John Smith',
            'customer_email' => 'john@example.com',
        ])
            ->assertCreated()
            ->assertJsonPath('data.offer_id', $offer->id);

        $this->postJson("/api/offers/{$offer->id}/reservations", [
            'client_reference' => 'order-2',
            'customer_name' => 'Jane Smith',
            'customer_email' => 'jane@example.com',
        ])->assertUnprocessable();

        $this->assertDatabaseCount('reservations', 1);
        $this->assertSame(0, $offer->fresh()->available_units);
    }
}
