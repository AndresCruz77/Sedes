<?php

namespace App\Models\Anticipos;

use Illuminate\Database\Eloquent\Model;

class REDIPS extends Model
{
    protected $table = 'RED.REDIPS';
    
    protected $fillable = 
    [
        'ipsID',
        'ipsRegimen',
        'ipsIDIPS',
        'ipsNombre',
        'ipsNitIPS',
        'ipsTipIpsTT',
        'ipsEstIPSTT',
        'ipsRepresentante',
        'ipsNivelAtencionTT',
        'ipsIDMinsalud',
        'ipsLicenciaSanitaria',
        'ipsLicenciaFuncionamiento',
        'ipsDireccion',
        'ipsTelefono1',
        'ipsTelefono2',
        'ipsAtencion',
        'ipsCelular',
        'ipsFax',
        'ipsBeeper',
        'ipsUbicacionTT',
        'ipsLocalidadTT',
        'ipsEmail',
        'ipspagweb',
        'ipsIDMunicipio',
        'ipsIDDivision',
        'ipsIDActEconomicaMuni',
        'ipsTipServicioTT',
        'ipsRegimenJuridicoTT',
        'ipsTipDocumentoTT',
        'ipsIDRemenTributarioTT',
        'ipsAutoretenedor',
        'ipsFchIniLabores',
        'ipsFchRadicacion',
        'ipsFchInactivacion',
        'ipsCtrlDocumentoTT',
        'ipsUsuario',
        'ipsFchAccion',
        'ipsTipAccionTT',
        'ipsHom',
        'ipsFchHabilitacion',
        'ipsAfiliacion',
        'ipsAseguradora',
        'ipssincronia',
        'ipsIDIpsRed',
        'ipsAutorizaciones',
        'ipsTipEntidad',
        'ipsValidarAG',
        'ipsEsEspecialista',
    ];

    protected $primaryKey = 'ipsID';
    
/* 
    const CREATED_AT = 'ARS_FECHA_CREACION';
    const UPDATED_AT = 'ARS_FECHA_MODIFICACION';     */
}
