<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   VentaElo extends Eloquent {

    protected $table = "venta"; // table name
    protected $fillable = [
        'venta_id',
        'venta_tipo',
        'id_cliente',
        'id_vendedor',
        'cajero_id',
        'caja_id',
        'local_id',
        'fecha',
        'venta_status',
        'subtotal',
        'gravado',
        'excluido',
        'total_impuesto',
        'total',
        'pagado',
        'descuento_valor',
        'descuento_porcentaje',
        'porcentaje_desc',
        'cambio',
        'devuelta',
        'regimen_contributivo',
        'desc_global',
        'total_otros_impuestos',
        'nota',
        'uuid',
        'fe_resolution_id',
        'fe_numero',
        'fe_reponseDian',
        'fe_zipkey',
        'fe_XmlFileName',
        'fe_issue_date',
    ];

    public $timestamps = false; //esto indica que no tiene el created_at ni el updated_at, par que
    // no los busque al momento de crear o actualizar
    protected $primaryKey = 'venta_id';

    /**
     * Método que deine la relacion de uno a muchos con detalle_venta
     */
    public function detalle_venta()
    {
        return $this->hasMany(DetalleVentaElo::class,'id_venta','venta_id');
    }
}