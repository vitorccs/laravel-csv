<?php

namespace Vitorccs\LaravelCsv\Tests\Data\Stubs;

use Illuminate\Database\Eloquent\Model;

class TestUser extends Model
{
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'active'
    ];
}
