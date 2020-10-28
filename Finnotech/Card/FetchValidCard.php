<?php

namespace App\Http\Services\Finnotech\Card;

use App\Http\Services\Finnotech\Auth;
use App\Http\Services\Finnotech\FinnotechService;
use App\Http\Services\Finnotech\Method;

class FetchValidCard extends FinnotechService
{
    protected $scope = 'oak:valid-card:get';
    protected $url = "/oak/v2/clients/{clientId}/getValidCard";
    protected $trackId = 'valid-card';
    protected $method = Method::POST;
    protected $auth = Auth::AuthorizationCode;
    protected $card;

    /**
     * FetchValidCard constructor.
     *
     * @param $card
     */
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
            'card'   => $this->card,
        ];
    }

    public function handle($response)
    {
        return $response->result;
    }
}