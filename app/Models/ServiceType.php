<?php

namespace App\Models;

use App\Services\Auth\YandexAuthService;

enum ServiceType: string
{
    case YANDEX_DIRECT = 'yandex_direct';
    case YANDEX_METRIKA = 'yandex_metrika';
    case YANDEX_BUSINESS = 'yandex_business';

    public function label(): string
    {
        return match($this) {
            self::YANDEX_DIRECT => 'Яндекс.Директ',
        };
    }

    public function authServiceClass(): string
    {
        return match($this) {
            self::YANDEX_DIRECT => YandexAuthService::class,
        };
    }

    public static function all(): array
    {
        return [
            self::YANDEX_DIRECT->value,
        ];
    }
}
