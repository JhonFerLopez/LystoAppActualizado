<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   ZonaElo extends Eloquent {

    protected $table = "zonas";
    protected $fillable = [ 'zona_id','zona_nombre','ciudad_id','status'];
    public $timestamps = false;
    protected $primaryKey = 'zona_id';



}