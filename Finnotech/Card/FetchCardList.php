<?php

namespace App\Http\Services\Finnotech\Card;

use App\Http\Services\Finnotech\FinnotechService;
use App\Http\Services\Finnotech\Method;

class FetchCardList extends FinnotechService
{
    protected $scope = 'card:list:get';
    protected $url = "/mpg/v2/clients/{clientId}/cards?mobile={mobile}";
    protected $trackId = 'card-list';
    protected $method = Method::GET;
    protected $mobile;

    public function __construct($mobile)
    {
        $this->mobile = $mobile;
    }

    protected function getParams(): array
    {
        return [
            '{clientId}' => config('finnotech.app'),
            '{mobile}'   => $this->mobile,
        ];
    }

    public function handle($response)
    {
        return $response->result;
    }
}