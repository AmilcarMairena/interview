<?php

namespace App\Http\Controllers;

use App\Contracts\WebServiceInterface;
use App\Http\Requests\DateValidatorRequest;
use Illuminate\Http\JsonResponse;

class SoapServiceController extends Controller
{
    //
    private WebServiceInterface $webService;
    function __construct(WebServiceInterface $webService)
    {
        $this->webService = $webService;
    }

    /**
     * @OA\Get(
     *     path="/api/available-variables",
     *     operationId="getAvailbleVariables",
     *     tags={"WebService"},
     *     summary="Consuming the Variables webservice method",
     *     @OA\Response(
     *       response="500",
     *       description="Server Error",
     *       @OA\JsonContent(
     *           type="object",
     *           @OA\Property (type="boolean",property="status",example="false"),
     *           @OA\Property (type="string",property="message",example="Something went wrong!"),
     *       )
     *      ),
     * )
     */
    public function availableVariables() : JsonResponse{
        list($status, $message, $totalCount, $variables) = $this->webService->availableVariables();

        if ( !$status ) {
            return $this->responseError($message);
        }
        return $this->responseWithData([
            'totalCount' => $totalCount,
            'variables' => $variables,
            ]);
    }

    /**
     * @OA\Post(
     *     path="/api/exchange-rate-range",
     *     operationId="getExchangeRateByRange",
     *     tags={"WebService"},
     *     summary="Consuming the TipoCambioRango webservice method",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(type="string", property="fechainit", example="01/01/2025" ),
     *               @OA\Property(type="string", property="fechafin", example="30/01/2025" ),
     *          ),
     *      ),
     *     @OA\Response(
     *       response="500",
     *       description="Server Error",
     *       @OA\JsonContent(
     *           type="object",
     *           @OA\Property (type="boolean",property="status",example="false"),
     *           @OA\Property (type="string",property="message",example="Something went wrong!"),
     *       )
     *      ),
     * )
     */
    public function exchangeRateRange(DateValidatorRequest $request) : JsonResponse{
        list($status, $message, $totalCount, $variables) = $this->webService->exchangeRateRange($request->validated());
        if ( !$status ) {
            return $this->responseError($message);
        }
        return $this->responseWithData([
            'exchange' => $totalCount,
            'totalCount' => $variables,
        ]);
    }
}
