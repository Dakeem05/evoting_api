<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tblparty extends Model
{
    use HasFactory;
    protected $guarded = [];
    use HasApiTokens, HasFactory, Notifiable;
}
