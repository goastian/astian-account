<?php

namespace App\Repositories\Traits;

use App\Models\Subscription\Scope;


trait Generic
{
    /**
     * Check if it the services is duplicated
     * @param mixed $value
     * @return array
     */
    public function checkServices($value)
    {
        $services = [];

        foreach ($value as $key) {
            $scope = Scope::with(['service'])->find($key);
            array_push($services, $scope->service->slug);
        }

        $count = array_count_values($services);

        $duplicated = array_keys(array_filter($count, function ($amount) {
            return $amount > 1;
        }));

        return $duplicated;
    }
}

