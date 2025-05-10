<?php

namespace App\Repository;

use App\Contracts\ExchangeRateInterface;
use App\Contracts\WebServiceInterface;
use App\Models\DataPerRequest;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Log;

class WebServiceRepository extends SoapConfiguration implements WebServiceInterface
{

    private ExchangeRateInterface $exchangeRate;

    function __construct(ExchangeRateInterface $exchangeRate){
        parent::__construct();

        $this->exchangeRate = $exchangeRate;
    }
    public function availableVariables() : mixed
    {
        // TODO: Implement variablesDisponibles() method.
        try {
            $result = $this->executeService(config('services.banguat.methods.availableVariables'));
            $total = $result->VariablesDisponiblesResult->TotalItems;
            $variables = array_map(function($item){
                return [
                    'moneda' => $item->moneda,
                    'descripcion' => $item->descripcion,
                ];
            },$result->VariablesDisponiblesResult->Variables->Variable);

            return [
                true,
                "Llamada de web service exitoso!",
                $total,
                $variables
            ];
        }catch (\Exception $exception){
            Log::error("Mapping error : " . $exception->getMessage());
            return [
                false,
                "Error al mapar llamada de web service!",
                null,
                null
            ];
        }

    }

    public function ExchangeRateRange(array $date) : mixed
    {
        // TODO: Implement TipoCambioRango() method.
        try {
            $result = $this->executeService(config('services.banguat.methods.ExchangeRateRange'),$date);
            $exchange_rate = $result->TipoCambioRangoResult->Vars->Var;
            $total_items = $result->TipoCambioRangoResult->TotalItems;


            //saving the results
            $this->exchangeRate->storeExchangeRate($exchange_rate,$date);

            return [
                true,
                "Llamada de web service exitoso!",
                $exchange_rate,
                $total_items
            ];
        }catch (\SoapFault $ex) {
            Log::error("Mapping error : " . $ex->getMessage());
            return [
                false,
                "Error al mapar llamada de web service!",
                null,
                null
            ];

        }
    }

}
