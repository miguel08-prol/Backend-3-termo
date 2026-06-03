<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Cliente extends Model
{
    protected $guarded = [];

    use Notifiable; 
}
