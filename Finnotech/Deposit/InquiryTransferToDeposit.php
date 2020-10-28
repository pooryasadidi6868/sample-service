<?php

namespace App\Http\Services\Finnotech\Deposit;

use App\Http\Services\Finnotech\Auth;
use App\Http\Services\Finnotech\FinnotechService;
use App\Http\Services\Finnotech\Method;

class InquiryTransferToDeposit extends FinnotechService
{
    protected $scope = 'oak:inquiry-transfer:get';
    protected $url = "/oak/v2/clients/{clientId}/transferInquiry?inquiryTrackId={inquiryTrackId}";
    protected $trackId = 'inquiry-transfer';
    protected $method = Method::GET;
    protected $auth = Auth::AuthorizationCode;
    protected $inquiryTrackId;

    /**
     * InquiryTransferToDeposit constructor.
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
            '{clientId}'       => config('finnotech.app'),
            '{inquiryTrackId}' => $this->inquiryTrackId,
        ];
    }

    public function handle($response)
    {
        return $response;
        //return fractal()
        //    ->collection($response->result->transactions)
        //    ->transformWith(new CardStatementTransformer)
        //    ->toArray();
    }
}