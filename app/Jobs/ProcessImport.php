<?php

namespace App\Jobs;

use App\Models\Import;
use App\Services\ImportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class ProcessImport implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 120;

    public function __construct(public readonly int $importId)
    {
    }

    public function handle(ImportService $service): void
    {
        $service->processNextBatch(Import::findOrFail($this->importId));
    }

    public function failed(?Throwable $exception): void
    {
        Import::whereKey($this->importId)->update([
            'status' => Import::STATUS_FAILED,
            'error' => $exception?->getMessage(),
        ]);
    }
}
