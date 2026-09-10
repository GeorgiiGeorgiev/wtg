<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Responses\ReservationResponse;
use App\Models\Offer;
use App\Services\ReservationService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends Controller
{
    public function store(
        StoreReservationRequest $request,
        Offer $offer,
        ReservationService $service,
    ): JsonResponse {
        $reservation = $service->create($offer, $request->validated());

        return (new ReservationResponse($reservation))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
