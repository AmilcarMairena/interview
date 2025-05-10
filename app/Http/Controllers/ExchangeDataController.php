<?php

namespace App\Http\Controllers;

use App\Contracts\ExchangeRateInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExchangeDataController extends Controller
{
    //

    private ExchangeRateInterface $exchangeRate;

    function __construct(ExchangeRateInterface $exchangeRate)
    {

        $this->exchangeRate = $exchangeRate;
    }

    public function index() : JsonResponse {
        list( $status, $message, $data ) = $this->exchangeRate->getExchangeRatePaginated();

        if ( !$status ) {
            return $this->responseError($message);
        }

        return $this->responseWithData($data);
    }
}
