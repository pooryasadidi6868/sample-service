<?php

namespace App\Http\Services\Finnotech\Token;

use App\Models\FinnotechToken;

class AuthorizationCodeTokenService extends TokenService
{
    protected function getBody(): array
    {
        return [
            'grant_type'    => 'refresh_token',
            'token_type'    => 'CODE',
            'bank'          => config('finnotech.bank_code'),
            'refresh_token' => find_finnotech_refresh_token($this->tokenId),
        ];
    }

    public function handle($response)
    {
        FinnotechToken::createOrUpdate('authorization_code', $response->result, $this->tokenId);
    }
}
