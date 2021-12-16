<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   CondicionesPagoElo extends Eloquent {

    protected $table = "condiciones_pago"; // table name
    protected $fillable = [ 'id_condiciones','dias','fe_payment_form_id','is_offer','nombre_condiciones',
        'status_condiciones'];
    public $timestamps = false;
    protected $primaryKey = 'id_condiciones';

    public function productos()
    {
        return $this->belongsToMany(ProductoElo::class,'producto_has_componente',
            'componente_id','producto_id');
    }


}