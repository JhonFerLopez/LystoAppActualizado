<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   VentaColumnasProductosElo extends Eloquent {

    protected $table = "venta_columas_productos"; // table name
    protected $fillable = [ 'id','nombre_columna','nombre_mostrar','mostrar','orden'];
    public $timestamps = false; //esto indica que no tiene el created_at ni el updated_at, par que
    // no los busque al momento de crear o actualizar
    protected $primaryKey = 'id';


}