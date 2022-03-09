<?php

namespace App\Models\recobros;

use Illuminate\Database\Eloquent\Model;

class SUR_SUSTITO_RECOBROS extends Model
{
    protected $fillable = 
    [
        'SUR_ID',
        'SUR_CODIGO_TECNOLOGIA_NOPBS',
        'SUR_CODIGO_TECNOLOGIA_PBS',
        'SUR_VALOR_UNITARIO_PBS',
        'SUR_VALOR_UNITARIO_NOPBS',
    ];

    protected $primarykey = 'SUR_ID';

}
