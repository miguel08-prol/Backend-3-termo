<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class clientes extends Model
{
    use HasFactory;

    /**
     * Define o nome da tabela explicitamente.
     * Use 'clientes' conforme o padrão que você está seguindo.
     */
    protected $table = 'clientes';

    /**
     * Atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'nome', 
        'cpf', 
        'email', 
        'telefone', 
        'endereco'
    ];

    /**
     * Relacionamento: Um cliente tem muitos pedidos.
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'cliente_id');
    }

    /**
     * ACESSOR E MUTATOR: Tratamento do Nome
     * - Salva no banco sempre em minúsculo (padronização).
     * - Exibe na View com iniciais maiúsculas, corrigindo conectivos (de, da, do).
     */
    protected function nome(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                $nomeFormatado = Str::title(mb_strtolower($value));
                
                // Correção de conectivos para nomes brasileiros
                $excecoes = [' De ', ' Da ', ' Do ', ' Dos ', ' Das ', ' E '];
                $corrigidos = [' de ', ' da ', ' do ', ' dos ', ' das ', ' e '];

                return str_replace($excecoes, $corrigidos, $nomeFormatado);
            },
            set: fn (string $value) => mb_strtolower($value),
        );
    }

    /**
     * ACESSOR: Formatação de Saída do CPF
     * Exibe o CPF com máscara (000.000.000-00) automaticamente.
     */
    protected function cpf(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                $limpo = preg_replace('/\D/', '', $value);
                if (strlen($limpo) === 11) {
                    return substr($limpo, 0, 3) . '.' . 
                           substr($limpo, 3, 3) . '.' . 
                           substr($limpo, 6, 3) . '-' . 
                           substr($limpo, 9, 2);
                }
                return $value;
            }
        );
    }

    /**
     * ACESSOR: Formatação de Saída do Telefone
     * Suporta (00) 0000-0000 ou (00) 90000-0000.
     */
    protected function telefone(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                $tel = preg_replace('/\D/', '', $value);
                if (strlen($tel) === 11) {
                    return '(' . substr($tel, 0, 2) . ') ' . substr($tel, 2, 5) . '-' . substr($tel, 7);
                } elseif (strlen($tel) === 10) {
                    return '(' . substr($tel, 0, 2) . ') ' . substr($tel, 2, 4) . '-' . substr($tel, 6);
                }
                return $value;
            }
        );
    }
}