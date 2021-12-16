<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   ClienteElo extends Eloquent {

    protected $table = "cliente";
    protected $fillable = [ 'id_cliente','ciudad_id','direccion','email','grupo_id','apellidos','nombres','identificacion',
        'telefono','celular','longitud','latitud','cliente_status','id_zona','digito_verificacion','sexo','fnac',
        'fecha_nacimiento','facturacion_maximo','valida_fact_maximo','valida_venta_credito','dias_credito',
        'codigo_interno','afiliado',
        'cantidad_domic','segundos_acum','segundos_promedio','string_promedio','permitir_deuda_vencida','saldo_inicial',
        'create_at','merchant_registration','fe_type_liability'];
    public $timestamps = false;
    protected $primaryKey = 'id_cliente';



}