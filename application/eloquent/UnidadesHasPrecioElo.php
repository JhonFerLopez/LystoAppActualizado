<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   UnidadesHasPrecioElo extends Eloquent {

    protected $table = "unidades_has_precio";
    protected $fillable = [ 'unidades_has_precio_id','id_condiciones_pago','id_unidad','id_producto','precio','utilidad','precio_minimo',
        'precio_maximo'];

    protected $primaryKey = 'unidades_has_precio_id';
    /**
     * @var bool
     * esto indica que no tiene el created_at ni el updated_at, par que
     * no los busque al momento de crear o actualizar
     */
    public $timestamps = false;

    public function producto() {
        return $this->belongsTo(ProductoElo::class,'id_producto','producto_id');
    }


}