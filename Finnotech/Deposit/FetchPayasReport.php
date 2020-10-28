<?php

namespace App\Http\Services\Finnotech\Deposit;

use App\Http\Services\Finnotech\Auth;
use App\Http\Services\Finnotech\FinnotechService;
use App\Http\Services\Finnotech\Method;
use Morilog\Jalali\Jalalian;

class FetchPayasReport extends FinnotechService
{
    protected $scope = 'oak:payas:get';
    protected $url = "/oak/v2/clients/{clientId}/deposits/{deposits}/payas?fromDate={fromDate}&toDate={toDate}";
    protected $trackId = 'payas-report';
    protected $method = Method::GET;
    protected $auth = Auth::AuthorizationCode;
    protected $deposit;
    protected $from;
    protected $to;

    /**
     * PayasReport constructor.
     *
     * @param $deposit
     * @param $from
     * @param $to
     */
    public function __construct($deposit, $from = null, $to = null)
    {
        $this->deposit = $deposit;
        $this->from = $from ?? now()->subMonths(1);
        $this->to = $to ?? now();
    }

    protected function getParams(): array
    {
        return [
            '{clientId}' => config('finnotech.app'),
            '{deposits}' => $this->deposit,
            '{fromDate}' => Jalalian::fromCarbon($this->from)->format('ymd'),
            '{toDate}'   => Jalalian::fromCarbon($this->to)->format('ymd'),
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