<?php

namespace App\Repository;

use App\Contracts\ExchangeRateInterface;
use App\Models\DataPerRequest;
use App\Models\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExchangeRateRepository implements ExchangeRateInterface
{

    public function storeExchangeRate(mixed $data, array $date): array
    {
        // TODO: Implement storeExchangeRate() method.

        DB::beginTransaction();
        try{

            $request = DataPerRequest::create([
                'init_date' => $date['fechainit'],
                'end_date'=> $date['fechafin'],
            ]);

            $id = $request->id;

            $mapping_result = array_map(function($item) use ( $id ){
                return [
                    'date' =>$item->fecha,
                    'er_sale' => $item->venta,
                    'er_purchase' => $item->compra,
                    'data_per_request_id' => $id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $data);


            ExchangeRate::insert($mapping_result);
            DB::commit();

            return [true, "Data loaded successfully"];

        }catch (\Exception $ex){
            DB::rollBack();
            Log::error($ex->getMessage());
            return [false, $ex->getMessage()];
        }

    }

    public function getExchangeRatePaginated(): array
    {
        // TODO: Implement getExchangeRatePaginated() method.
        try {
            $data = ExchangeRate::join('data_per_requests', 'data_per_requests.id', '=', 'exchange_rates.data_per_request_id')
                ->select('data_per_requests.id', 'exchange_rates.date','exchange_rates.er_sale', 'exchange_rates.er_purchase')
                ->paginate(10);
            return [true,"Datos encontrados", $data];
        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return [false, $exception->getMessage(), null];
        }

    }
}
