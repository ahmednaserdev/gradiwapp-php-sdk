<?php

namespace GradiWapp\Sdk\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for GradiWapp SDK Client
 *
 * @method static \GradiWapp\Sdk\Resources\Messages messages()
 * @method static \GradiWapp\Sdk\Resources\Webhooks webhooks()
 */
class GradiWapp extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'gradiwapp.client';
    }
}

