<?php

declare(strict_types=1);

namespace Nile\LaravelServer\Traits;

use Illuminate\Support\Facades\App;
use Nile\LaravelServer\Scopes\TenantScope;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model) {
            if (App::has('tenant_id')) {
                $model->tenant_id = App::get('tenant_id');
            }
        });
    }

    public function initializeBelongsToTenant(): void
    {
        if (! isset($this->casts['tenant_id'])) {
            $this->casts['tenant_id'] = 'string';
        }
    }
}
