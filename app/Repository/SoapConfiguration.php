<?php

namespace App\Repository;
use SoapClient;

class SoapConfiguration
{

    private $soap_client = null;

    /**
     * @throws \SoapFault
     */
    function __construct()
    {
        $this->soap_client = new SoapClient(config('services.banguat.wsdl'), [
            'trace' => 1,
            'exceptions' => true,
        ]);
    }

    public function executeService(string $method_name, array $parameters = []) : mixed{
      return $this->soap_client->$method_name([...$parameters]);
    }


}
