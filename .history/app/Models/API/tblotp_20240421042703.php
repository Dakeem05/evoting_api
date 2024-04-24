<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\Contracts\HasApiTokens
class tblotp extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'voterID'
    ];
    use HasApiTokens, HasFactory, Notifiable;
}
