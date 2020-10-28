<?php

namespace App\Http\Services\Finnotech\Deposit;

use App\Http\Services\Finnotech\Auth;
use App\Http\Services\Finnotech\FinnotechService;
use App\Http\Services\Finnotech\Method;
use Morilog\Jalali\Jalalian;

class FetchDepositStatement extends FinnotechService
{
    protected $scope = 'oak:statement:get';
    protected $url = "/oak/v2/clients/{clientId}/deposits/{deposit}/statement?fromDate={fromDate}&toDate={toDate}&fromTime={fromTime}&toTime={toTime}";
    protected $trackId = 'deposit-statement';
    protected $method = Method::GET;
    protected $auth = Auth::AuthorizationCode;
    protected $deposit;
    protected $from;
    protected $to;

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
            '{deposit}'  => $this->deposit,
            '{fromDate}' => Jalalian::fromCarbon($this->from)->format('ymd'),
            '{toDate}'   => Jalalian::fromCarbon($this->to)->format('ymd'),
            '{fromTime}' => '000000',
            '{toTime}'   => '235959',
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