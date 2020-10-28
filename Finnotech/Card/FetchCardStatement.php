<?php

namespace App\Http\Services\Finnotech\Card;

use App\Http\Services\Finnotech\FinnotechService;
use App\Http\Services\Finnotech\Method;
use App\Transformers\CardStatementTransformer;
use Morilog\Jalali\Jalalian;

class FetchCardStatement extends FinnotechService
{
    protected $scope = 'oak:card-statement:get';
    protected $url = "/oak/v2/clients/{clientId}/card/statement";
    protected $trackId = 'card-statement';
    protected $method = Method::POST;
    protected $card;
    protected $from;
    protected $to;

    public function __construct($card, $from = null, $to = null)
    {
        $this->card = $card;
        $this->from = $from ?? now()->subMonths(1);
        $this->to = $to ?? now();
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
            'card'     => $this->card,
            'fromDate' => Jalalian::fromCarbon($this->from)->format('ymd'),
            'toDate'   => Jalalian::fromCarbon($this->to)->format('ymd'),
        ];
    }

    public function handle($response)
    {
        $transactions = $response->result->transactions;
        $transactions = $this->calcTransactionAction($transactions);
        $transactions = fractal()->collection($transactions)->transformWith(new CardStatementTransformer)->toArray();
        $transactions = $this->sortTransactions($transactions['data'])->toArray();

        return $transactions ?? [];
    }

    private function calcTransactionAction($resp)
    {
        $available = 0;
        foreach ($resp as $row) {
            $row->action = ($row->available > $available) ? '+' : '-';
            $row->actionName = ($row->available > $available) ? 'Top-up' : 'Spend';
            $available = $row->available;
        }

        return $resp;
    }

    private function sortTransactions($transactions)
    {
        return collect($transactions)->sortByDesc('timestamp');
    }
}
