<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class NotificacionElo extends Eloquent {

    protected $table = "notificaciones"; // table name
    protected $fillable = [ 'id','fecha','titulo','mensaje','topic','aplicacion'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}