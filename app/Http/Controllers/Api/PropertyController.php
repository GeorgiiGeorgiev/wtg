<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchPropertiesRequest;
use App\Http\Responses\PropertyCollectionResponse;
use App\Services\PropertyService;

class PropertyController extends Controller
{
    public function index(
        SearchPropertiesRequest $request,
        PropertyService $service,
    ): PropertyCollectionResponse {
        return new PropertyCollectionResponse(
            $service->search($request->validated()),
        );
    }
}
