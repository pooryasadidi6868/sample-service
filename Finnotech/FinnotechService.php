<?php

namespace App\Http\Services\Finnotech;

use App\Models\FinnotechLog;
use App\Http\Services\BaseHttpClient;
use App\Http\Services\Loggable;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

abstract class FinnotechService extends BaseHttpClient
{
    use Loggable;

    protected $scope;
    protected $url;
    protected $body = [];
    protected $trackId = '';
    protected $method = Method::GET;
    protected $auth = Auth::ClientCredential;
    protected $authType = AuthType::Bearer;

    public $generatedTrackId = null;
    public $result = null;

    public function __construct($body = [])
    {
        $this->body = $body;
    }

    public static function dispatchNow()
    {
        $service = new static(...func_get_args());

        $service->generatedTrackId = (strlen($service->trackId) ? $service->trackId . '-' : '') . Str::random(26);
        $service->cache();

        $url = strtr($service->url, $service->getParams());
        $url = self::addQueryString($url, 'trackId', $service->generatedTrackId);

        $body = array_merge($service->getBody(), $service->body);
        $response = $service->run($service->method, $url, $body);
        $service->updateCache($response);
        $responseObj = json_decode($response);

        $service->result = $service->handle($responseObj);
        return  $service;
    }

    public static function dispatchNowIf($boolean, ...$arguments)
    {
        if ($boolean)
            return self::dispatchNow(...$arguments);
    }

    protected function getClient(): Client
    {
        return new Client(
            [
                'base_uri' => config('finnotech.base'),
                'verify'   => false,
                'headers'  => [
                    'Accept'        => 'application/json',
                    'Content-type'  => 'application/json',
                    'Authorization' => $this->authType . ' ' . $this->getToken(),
                ],
            ]
        );
    }

    protected function getToken(): string
    {
        return get_finnotech_token_with_scope($this->scope);
    }

    protected function getParams(): array
    {
        return [];
    }

    protected function getBody(): array
    {
        return [];
    }

    private function cache()
    {
        $this->log = FinnotechLog::create([
            'service'  => self::class,
            'scope'    => $this->scope,
            'track_id' => $this->generatedTrackId,
            'payload'  => serialize($this),
        ]);
    }

    private function updateCache($response)
    {
        if (! $this->log)
            return;

        $this->log->response = $response;
        $this->log->status = 'completed';
        $this->log->save();
    }

    abstract public function handle($response);
}

abstract class Method
{
    const GET = 'get';
    const POST = 'post';
}

abstract class Auth
{
    const ClientCredential = 'client_credential_token';
    const AuthorizationCode = 'authorization_code_token';
}

abstract class AuthType
{
    const Basic = 'Basic';
    const Bearer = 'Bearer';
}
