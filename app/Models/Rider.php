<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    use HasFactory;
    protected $fillable = [
        'firstname',
        'lastname',
        'profile_pic',
        'dob',
        'email',
        'license_no',
        'license_front',
        'national_id',
    ];
}
