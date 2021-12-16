<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   GrupoElo extends Eloquent {

    protected $table = "grupos"; // table name
    protected $fillable = [ 'id_grupo','nombre_grupo','estatus_grupo','nivel_id','codigo'];
    public $timestamps = false;
    protected $primaryKey = 'id_grupo';

    public function nivel()
    {
        return $this->hasOne(NivelesGrupoElo::class,'nivel_id');
    }

    public function product()
    {
        return $this->hasOne(ProductoElo::class,'produto_grupo');
    }
}