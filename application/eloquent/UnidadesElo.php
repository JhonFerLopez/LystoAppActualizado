<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   UnidadesElo extends Eloquent {

    protected $table = "unidades"; // table name
    protected $fillable = [ 'id_unidad','nombre_unidad','abreviatura','orden','estatus_unidad','fe_unidad'];
    public $timestamps = false; //esto indica que no tiene el created_at ni el updated_at, par que
        // no los busque al momento de crear o actualizar
    protected $primaryKey = 'id_unidad';

    public function productos()
    {
        return $this->belongsToMany(ProductoElo::class);
    }

}