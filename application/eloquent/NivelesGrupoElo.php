<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class NivelesGrupoElo extends Eloquent {

    protected $table = "niveles_grupos"; // table name
    protected $fillable = [ 'nivel_id','nombre_nivel','nivel','estatus'];

    public function grupo()
    {
        return $this->belongsTo(GrupoElo::class);
    }
}