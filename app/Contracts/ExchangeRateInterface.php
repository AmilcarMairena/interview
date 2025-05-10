<?php

namespace App\Contracts;

interface ExchangeRateInterface
{
    public function storeExchangeRate(mixed $data, array $date) : array;
    public function getExchangeRatePaginated() : mixed;
}
