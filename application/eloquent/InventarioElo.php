<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   InventarioElo extends Eloquent {

    protected $table = "inventario"; // table name
    protected $fillable = [ 'id_inventario','id_producto','cantidad','id_local','id_unidad'];
    public $timestamps = false; //esto indica que no tiene el created_at ni el updated_at, par que
    // no los busque al momento de crear o actualizar
    protected $primaryKey = 'id_inventario';

}