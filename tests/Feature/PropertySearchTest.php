<?php

namespace Tests\Feature;

use App\Models\Import;
use App\Models\Offer;
use App\Models\Property;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertySearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_returns_cheapest_current_offer_for_each_property(): void
    {
        $supplier = Supplier::factory()->create(['code' => 'supplier-a']);
        $import = Import::factory()->for($supplier)->create();
        $barcelona = Property::factory()->create(['city' => 'Barcelona']);
        $madrid = Property::factory()->create(['city' => 'Madrid']);
        $checkIn = now()->addMonth()->toDateString();
        $checkOut = now()->addMonth()->addDays(5)->toDateString();

        Offer::factory()->for($supplier)->for($import)->for($barcelona)->create([
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'price' => 90000,
        ]);

        $bestOffer = Offer::factory()->for($supplier)->for($import)->for($barcelona)->create([
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'price' => 70000,
        ]);

        Offer::factory()->for($supplier)->for($import)->for($barcelona)->create([
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'price' => 10000,
            'expires_at' => now()->subMinute(),
        ]);

        Offer::factory()->for($supplier)->for($import)->for($madrid)->create([
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'price' => 50000,
        ]);

        $response = $this->getJson('/api/properties?'.http_build_query([
            'city' => 'Barcelona',
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'guests' => 2,
        ]));

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.best_offer.id', $bestOffer->id)
            ->assertJsonPath('data.0.best_offer.price', 70000)
            ->assertJsonPath('next', null)
            ->assertJsonPath('prev', null)
            ->assertJsonPath('per_page', 15);
    }
}
