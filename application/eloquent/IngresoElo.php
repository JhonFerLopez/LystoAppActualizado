<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class IngresoElo extends Eloquent {

    protected $table = "ingreso"; // table name
    protected $fillable = [
        'id_ingreso',
        'condicion_pago',
        'fecha_registro',
        'int_Proveedor_id',
        'nUsuCodigo',
        'local_id',
        'tipo_documento',
        'documento_numero',
        'fecha_emision',
        'ingreso_status',
        'impuesto_ingreso',
        'sub_total_ingreso',
        'total_ingreso',
        'total_bonificado',
        'total_descuento',
        'tipo_carga',
        'last_update'
    ];
    public $timestamps = false;
    protected $primaryKey = 'id_ingreso';


    /**
     * Método que deine la relacion de uno a muchos
     */
    public function detalleingreso()
    {
        return $this->hasMany(DetalleIngresoElo::class,'id_ingreso','id_ingreso');
    }
}