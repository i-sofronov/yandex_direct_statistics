<?php

namespace App\Services\Auth;

use App\Models\ServiceType;

class AuthManager
{
    public function getService(ServiceType $serviceType){
        $class = $serviceType->authServiceClass();
        return app($class);
    }

    public function getAuthUrl(ServiceType $serviceType, string $state): string{
        return $this->getService($serviceType)->getAuthUrl($state);
    }

    public function handleCallback(ServiceType $serviceType, array $params): AuthResult{
        return $this->getService($serviceType)->handleCallback($params);
    }
}
