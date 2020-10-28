<?php

namespace App\Http\Services\Finnotech\Card;

use App\Exceptions\FinnotechNullResponseException;
use App\Http\Services\Finnotech\FinnotechService;

class FetchCardInformation extends FinnotechService
{
    protected $scope = 'card:information:get';
    protected $url = "/mpg/v2/clients/{clientId}/cards/{card}";
    protected $trackId = 'card-info';
    protected $card;

    public function __construct($card)
    {
        $this->card = $card;
    }

    protected function getParams(): array
    {
        return [
            '{clientId}' => config('finnotech.app'),
            '{card}'     => $this->card,
        ];
    }

    public function handle($response)
    {
        return $response;
    }
}
