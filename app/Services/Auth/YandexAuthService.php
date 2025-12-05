<?php
namespace App\Services\Auth;

use App\Http\Clients\YandexAuthClient;
use App\Http\Clients\YandexDirectClient;
use App\Http\Clients\YandexMetrikaClient;
use App\Models\ServiceType;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class YandexAuthService
{
    public function __construct(
        private YandexAuthClient $oauthClient,
        private YandexDirectClient $directClient,
    ) {}

    public function getAuthUrl(ServiceType $serviceType, string $state): string
    {
        $params = [
            'response_type' => 'code',
            'client_id' => Config::get('services.yandex.client_id'),
            'state' => $state,
            'force_confirm' => true,
            'service_type' => $serviceType,
            'redirect_uri' => Config::get('services.yandex.redirect_uri'),
        ];

        return 'https://oauth.yandex.ru/authorize?' . http_build_query($params);
    }

    public function handleCallback(array $params): array
    {
        if (!isset($params['code'])) {
            throw new \Exception('Authorization code not provided');
        }

        return $this->oauthClient->exchangeCodeForToken($params['code']);
    }

    public function getAccountInfo(ServiceType $serviceType, string $accessToken): array
    {
        return match($serviceType) {
            ServiceType::YANDEX_DIRECT => $this->getDirectAccountInfo($accessToken),
        };
    }

    private function getDirectAccountInfo(string $accessToken): array
    {
        $this->directClient->setAccessToken($accessToken);
        $accountInfo = $this->directClient->getAccountInfo();

        return [
            'service_type' => 'yandex_direct',
            'account_name' => $accountInfo['Login'] ?? $accountInfo['AccountID'] ?? 'Неизвестно',
            'amount' => (float) $accountInfo['Amount'] ?? 0,
        ];
    }
}
