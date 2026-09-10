<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreImportRequest;
use App\Http\Responses\ShowImportResponse;
use App\Http\Responses\StoreImportResponse;
use App\Models\Import;
use App\Services\ImportService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ImportController extends Controller
{
    public function store(StoreImportRequest $request, ImportService $service): JsonResponse
    {
        $import = $service->create($request->validated());

        return (new StoreImportResponse($import))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function show(Import $import): ShowImportResponse
    {
        return new ShowImportResponse($import->load('supplier'));
    }
}
