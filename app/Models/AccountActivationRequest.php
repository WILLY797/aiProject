<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountActivationRequest extends Model
{
    protected $fillable = [
        'business_name',
        'contact_name',
        'email',
        'phone',
        'account_number',
    ];
}
