<?php

declare(strict_types=1);

namespace Nile\LaravelServer\Models;

use Nile\LaravelServer\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

abstract class NileModel extends Model
{
    use BelongsToTenant;
}
