<?php

namespace App\Http\Services\Finnotech\Deposit;

use App\Http\Services\Finnotech\Auth;
use App\Http\Services\Finnotech\FinnotechService;
use App\Http\Services\Finnotech\Method;

class TransferToDeposit extends FinnotechService
{
    protected $scope = 'oak:transfer-to:execute';
    protected $url = "/oak/v2/clients/{clientId}/transferTo";
    protected $trackId = 'transfer-to-deposit';
    protected $method = Method::POST;
    protected $auth = Auth::AuthorizationCode;
    protected $amount;
    protected $description;
    protected $destinationFirstName;
    protected $destinationLastName;
    protected $destinationNumber;
    protected $paymentNumber;

    /**
     * TransferToDeposit constructor.
     *
     * @param $amount
     * @param $description
     * @param $destinationFirstName
     * @param $destinationLastName
     * @param $destinationNumber
     * @param $paymentNumber
     */
    public function __construct(
        $amount, $description, $destinationFirstName, $destinationLastName, $destinationNumber, $paymentNumber
    ) {
        $this->amount = $amount;
        $this->description = $description;
        $this->destinationFirstName = $destinationFirstName;
        $this->destinationLastName = $destinationLastName;
        $this->destinationNumber = $destinationNumber;
        $this->paymentNumber = $paymentNumber;
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
            'amount'               => $this->amount,
            'description'          => $this->description,
            'destinationFirstname' => $this->destinationFirstName,
            'destinationLastname'  => $this->destinationLastName,
            'destinationNumber'    => $this->destinationNumber,
            'paymentNumber'        => $this->paymentNumber,
        ];
    }

    public function handle($response)
    {
        return $response;
    }
}
