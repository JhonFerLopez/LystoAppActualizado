<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   ConfiguracionesElo extends Eloquent {

    protected $table = "configuraciones"; // table name
    protected $fillable = [ 'config_id','config_key','config_value'];
    public $timestamps = false; //esto indica que no tiene el created_at ni el updated_at, par que
    // no los busque al momento de crear o actualizar
    protected $primaryKey = 'config_id';

}