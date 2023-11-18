<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   ProductoCodigoBarraElo extends Eloquent {

    protected $table = "producto_codigo_barra"; // table name
    protected $fillable = [ 'producto_codigo_barra_id','codigo_barra','producto_id'];
    public $timestamps = false; //esto indica que no tiene el created_at ni el updated_at, par que
    // no los busque al momento de crear o actualizar
    protected $primaryKey = 'producto_codigo_barra_id';

    public function producto()
    {
        return $this->belongsTo(ProductoElo::class,'producto_id','producto_id');
    }

}