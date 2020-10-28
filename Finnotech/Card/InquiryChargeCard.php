<?php

namespace App\Http\Services\Finnotech\Card;

use App\Http\Services\Finnotech\Auth;
use App\Http\Services\Finnotech\FinnotechService;
use App\Http\Services\Finnotech\Method;

class InquiryChargeCard extends FinnotechService
{
    protected $scope = 'oak:card-charge-inquiry:get';
    protected $url = "/oak/v2/clients/{clientId}/inquiryChargeCard";
    protected $trackId = 'inquiry-charge-card';
    protected $method = Method::POST;
    protected $auth = Auth::AuthorizationCode;
    protected $inquiryTrackId;

    /**
     * InquiryChargeCard constructor.
     *
     * @param $inquiryTrackId
     */
    public function __construct($inquiryTrackId)
    {
        $this->inquiryTrackId = $inquiryTrackId;
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
            'trackId' => $this->inquiryTrackId,
        ];
    }

    public function handle($response)
    {
        return $response->result;
    }
}