<?php

namespace App\Http\Services\Finnotech\Card;

use App\Http\Services\Finnotech\FinnotechService;
use App\Http\Services\Finnotech\Method;

class FetchCardBalance extends FinnotechService
{
    protected $scope = 'oak:card-balance:get';
    protected $url = "/oak/v2/clients/{clientId}/card/balance";
    protected $trackId = 'card-balance';
    protected $method = Method::POST;
    protected $card;

    public function __construct($card)
    {
        $this->card = $card;
    }

    protected function getParams(): array
    {
        return [
            '{clientId}' => config('finnotech.app'),
        ];
    }

    protected function getBody(): array
    {
        return [
            'card' => $this->card,
        ];
    }

    public function handle($response)
    {
        if (isset($response->result))
            return $response->result->balance;
        else
            return '';
    }
}
