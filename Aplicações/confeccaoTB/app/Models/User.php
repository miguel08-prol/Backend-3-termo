<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * MUTATOR: Formata o nome antes de salvar no banco
     */
    public function setNameAttribute($value)
    {
        // Converte para "Nome Sobrenome" (Capitalize)
        $this->attributes['name'] = Str::title(mb_strtolower($value));
    }

    /**
     * ACCESSOR: Garante que ao exibir o nome, ele esteja sempre correto
     */
    public function getNameAttribute($value)
    {
        return Str::title($value);
    }
}