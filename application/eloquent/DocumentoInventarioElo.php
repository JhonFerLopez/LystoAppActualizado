<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   DocumentoInventarioElo extends Eloquent {

    protected $table = "documentos_inventarios";
    protected $fillable = [ 'documento_id','documento_nombre','documento_tipo','deleted_at'];
    public $timestamps = false;
    protected $primaryKey = 'documento_id';


}