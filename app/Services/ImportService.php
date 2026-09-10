<?php

namespace App\Services;

use App\Jobs\ProcessImport;
use App\Models\Import;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class ImportService
{
    private const BATCH_SIZE = 100;

    public function __construct(private readonly OfferService $offerService) {}

    public function create(array $data): Import
    {
        $supplier = Supplier::where('code', $data['supplier'])->firstOrFail();

        $import = Import::firstOrCreate(
            [
                'supplier_id' => $supplier->id,
                'external_import_id' => $data['external_import_id'],
            ],
            [
                'sent_at' => $data['sent_at'],
                'status' => Import::STATUS_PENDING,
                'total_offers' => count($data['offers']),
                'processed_offers' => 0,
                'payload' => $data['offers'],
            ],
        );

        if ($import->wasRecentlyCreated) {
            ProcessImport::dispatch($import->id)->afterCommit();
        }

        return $import;
    }

    public function processNextBatch(Import $import): void
    {
        DB::transaction(function () use ($import): void {
            $import = Import::lockForUpdate()->findOrFail($import->id);

            if ($import->status === Import::STATUS_COMPLETED) {
                return;
            }

            $offers = array_slice(
                $import->payload,
                $import->processed_offers,
                self::BATCH_SIZE,
            );

            $import->update([
                'status' => Import::STATUS_PROCESSING,
                'error' => null,
            ]);

            foreach ($offers as $offer) {
                $storedOffer = $this->offerService->store($import, $offer);

                DB::table('import_history')->insertOrIgnore([
                    'import_id' => $import->id,
                    'supplier_id' => $import->supplier_id,
                    'offer_id' => $storedOffer->offer->id,
                    'property_id' => $storedOffer->property->id,
                ]);
            }

            $processedOffers = $import->processed_offers + count($offers);
            $completed = $offers === [] || $processedOffers >= $import->total_offers;

            $import->update([
                'status' => $completed ? Import::STATUS_COMPLETED : Import::STATUS_PROCESSING,
                'processed_offers' => $processedOffers,
                'completed_at' => $completed ? now() : null,
            ]);

            if (! $completed) {
                ProcessImport::dispatch($import->id)->afterCommit();
            }
        });
    }
}
