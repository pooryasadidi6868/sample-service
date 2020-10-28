<?php

namespace App\Http\Services\Finnotech\Token;

use App\Models\FinnotechToken;

class ClientCredentialTokenService extends TokenService
{
    protected function getBody(): array
    {
        return [
            'grant_type'    => 'refresh_token',
            'token_type'    => 'CLIENT-CREDENTIAL',
            'refresh_token' => find_finnotech_refresh_token($this->tokenId),
        ];
    }

    public function handle($response)
    {
        FinnotechToken::createOrUpdate('client_credential', $response->result, $this->tokenId);
    }
}
