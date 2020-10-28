<?php

namespace App\Http\Services\Finnotech\Token;

use App\Http\Services\Finnotech\AuthType;
use App\Http\Services\Finnotech\FinnotechService;
use App\Http\Services\Finnotech\Method;

abstract class TokenService extends FinnotechService
{
    protected $url = '/dev/v2/oauth2/token';
    protected $trackId = false;
    protected $method = Method::POST;
    protected $authType = AuthType::Basic;
    protected $tokenId;

    public function __construct($tokenId)
    {
        parent::__construct();
        $this->tokenId = $tokenId;
    }

    protected function getToken(): string
    {
        $app = config('finnotech.app');
        $secret = config('finnotech.secret');

        return base64_encode($app . ':' . $secret);
    }
}