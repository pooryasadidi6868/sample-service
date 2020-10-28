<?php

namespace App\Http\Services\Finnotech\Token;

use App\Http\Services\Finnotech\FinnotechService;
use App\Http\Services\Finnotech\Method;

class GetTokens extends FinnotechService
{
    protected $scope = 'boomrang:tokens:get';
    protected $url = '/dev/v2/clients/{clientId}/tokens?nid={nid}&bank={bank}';
    protected $method = Method::GET;

    protected function getParams(): array
    {
        return [
            '{clientId}' => config('finnotech.app'),
            '{nid}'      => config('finnotech.code'),
            '{bank}'     => config('finnotech.bank_code'),
        ];
    }

    public function handle($response)
    {
        return $response;
    }
}