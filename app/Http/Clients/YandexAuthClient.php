<?php

namespace App\Http\Clients;

use App\Models\ServiceType;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Config;

class YandexAuthClient
{
    private Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => 'https://oauth.yandex.ru/',
            'timeout' => 30,
        ]);
    }

    public function exchangeCodeForToken(string $code): array
    {
        try {
            $response = $this->httpClient->post('token', [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'client_id' => Config::get('services.yandex.client_id'),
                    'client_secret' => Config::get('services.yandex.client_secret'),
                    'redirect_uri' => Config::get('services.yandex.redirect_uri')
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            return [
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'] ?? null,
                'expires_in' => $data['expires_in'],
                'token_type' => $data['token_type'],
            ];

        } catch (RequestException $e) {
            throw new \Exception("Yandex OAuth error: " . $e->getMessage());
        }
    }

    public function refreshToken(string $refreshToken, ServiceType $serviceType): void
    {}
}
