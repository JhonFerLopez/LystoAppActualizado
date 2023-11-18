<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   CiudadElo extends Eloquent {

    protected $table = "ciudades";
    protected $fillable = [ 'ciudad_id','ciudad_nombre','estado_id'];
    public $timestamps = false;
    protected $primaryKey = 'ciudad_id';

    /**
     * Método que deine la relacion de uno a muchos
     */
    public function barrios()
    {
        return $this->hasMany(BarrioElo::class,'ciudad_id','ciudad_id');
    }


}