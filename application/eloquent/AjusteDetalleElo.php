<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   AjusteDetalleElo extends Eloquent {

    protected $table = "ajustedetalle";
    protected $fillable = [ 'id_ajustedetalle','id_ajusteinventario','cantidad_detalle','old_cantidad','id_inventario',
        'costo','id_ubicacion'];
    public $timestamps = false;
    protected $primaryKey = 'id_ajustedetalle';


    public function ajusteinventario()
    {
        return $this->belongsTo(AjusteInventarioElo::class,'id_ajusteinventario','id_ajusteinventario');
    }

    /*
    public function inventario()
    {
        return $this->hasOne(InventarioElo::class,'id_inventario','id_inventario');
    }

   */


}