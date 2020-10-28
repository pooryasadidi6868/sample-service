<?php

namespace App\Http\Services\Finnotech\IBan;

use App\Http\Services\Finnotech\FinnotechService;
use App\Http\Services\Finnotech\Method;

class FetchIBanInquiry extends FinnotechService
{
    protected $scope = 'oak:iban-inquiry:get';
    protected $url = "/oak/v2/clients/{clientId}/ibanInquiry?iban={iban}";
    protected $trackId = 'iban-inquiry';
    protected $method = Method::GET;
    protected $iban;

    public function __construct($iban)
    {
        $this->iban = $iban;
    }

    protected function getParams(): array
    {
        return [
            '{clientId}' => config('finnotech.app'),
            '{iban}'     => $this->iban,
        ];
    }

    public function handle($response)
    {
        return $response;
    }
}
