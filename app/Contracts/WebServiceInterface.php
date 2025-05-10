<?php

namespace App\Contracts;

interface WebServiceInterface
{
        public function availableVariables() : mixed;
        public function ExchangeRateRange(array $date) : mixed;
}
