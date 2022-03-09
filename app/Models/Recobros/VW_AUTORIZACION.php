<?php

namespace App\Models\recobros;

use Illuminate\Database\Eloquent\Model;

class VW_AUTORIZACION extends Model
{
    protected $table = 'RECOBROS.AUTORIZACION';
    
    protected $fiable = 
    [
      'rptNoEntrega',
      'rptIdDireccionamiento',
      'rptIdEPS',
      'AUTIDIPS',
      'rptCanToAEntregar',
      'rptCodSerTecAEntregar',
      'rptNoIDPaciente',
      'rptTipoIDPaciente',
      'rptNoPrescripcion',
      'rptIdAutorizacion',
      'ipsIDips',
      'ipsNombre',
      'ipsNitIps',
      'AUTFCHAUTORIZACION'
    ];
}