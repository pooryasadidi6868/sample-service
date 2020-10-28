<?php

namespace App\Http\Services\Finnotech\Card;

use App\Http\Services\Finnotech\Auth;
use App\Http\Services\Finnotech\FinnotechService;
use App\Http\Services\Finnotech\Method;

class ChargeCard extends FinnotechService
{
    protected $scope = 'oak:card-charge:execute';
    protected $url = "/oak/v2/clients/{clientId}/deposits/{deposit}/chargeCard";
    protected $trackId = 'charge-card';
    protected $method = Method::POST;
    protected $auth = Auth::AuthorizationCode;
    protected $deposit;
    protected $amount;
    protected $card;

    public function __construct($deposit, $amount, $card)
    {
        $this->deposit = $deposit;
        $this->amount = $amount;
        $this->card = $card;
    }

    protected function getParams(): array
    {
        return [
            '{clientId}' => config('finnotech.app'),
            '{deposit}'  => $this->deposit,
        ];
    }

    protected function getBody(): array
    {
        return [
            'amount' => $this->amount,
            'card'   => $this->card,
        ];
    }

    public function handle($response)
    {
        return $response;
    }
}
