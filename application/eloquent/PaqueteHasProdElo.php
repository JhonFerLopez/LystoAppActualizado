<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   PaqueteHasProdElo extends Eloquent {

    protected $table = "paquete_has_prod"; // table name
    protected $fillable = [ 'paquete_has_prod_id','paquete_id','prod_id','unidad_id','cantidad'];
    public $timestamps = false; //esto indica que no tiene el created_at ni el updated_at, par que
    // no los busque al momento de crear o actualizar
    protected $primaryKey = 'paquete_has_prod_id';

    //protected $with = ['producto_paquete'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * el producto padre, es decir, el que es el paquete.
     */
    public function paquete_padre()
    {
        return $this->belongsTo(ProductoElo::class,'paquete_id','producto_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * Productos que pertenecen al paquete
     */
    public function producto_paquete()
    {
        return $this->belongsTo(ProductoElo::class,'prod_id','producto_id');
    }

}