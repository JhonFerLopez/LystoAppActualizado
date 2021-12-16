<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   AjusteInventarioElo extends Eloquent {

    protected $table = "ajusteinventario";
    protected $fillable = [ 'id_ajusteinventario','tipo_ajuste','local_id','usuario','fecha'];
    public $timestamps = false;
    protected $primaryKey = 'id_ajusteinventario';



    public function ajustedetalle()
    {
        return $this->hasMany(AjusteDetalleElo::class,'id_ajusteinventario','id_ajusteinventario');
    }


}