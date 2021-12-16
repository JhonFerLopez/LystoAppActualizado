<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   DepartamentoElo extends Eloquent {

    protected $table = "estados";
    protected $fillable = [ 'estados_id','estados_nombre','pais_id'];
    public $timestamps = false;
    protected $primaryKey = 'estados_id';

    public function allTablesRelations(){
        return array(
            'ciudades'
        );
    }

    /**
     * Método que deine la relacion de uno a muchos
     */
    public function ciudades()
    {
        return $this->hasMany(CiudadElo::class,'estado_id','estados_id');
    }

}