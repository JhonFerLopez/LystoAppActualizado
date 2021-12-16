<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class ProductoElo extends Eloquent {

    protected $table = "producto"; // table name
    protected $fillable = [
        'producto_id',
        'producto_codigo_interno',
        'producto_nombre',
        'produto_grupo',
        'producto_gruponvldos',
        'producto_gruponvltres',
        'producto_tipo',
        'producto_clasificacion',
        'producto_sustituto',
        'producto_proveedor',
        'producto_impuesto',
        'producto_ubicacion_fisica',
        'producto_presentacion',
        'producto_estatus',
        'producto_activo',
        'costo_unitario',
        'producto_mensaje',
        'costo_promedio',
        'producto_descuentos',
        'costo_cargue',
        'producto_comision',
        'producto_bonificaciones',
        'porcentaje_descuento',
        'precio_abierto',
        'control_inven',
        'control_inven_diario',
        'is_paquete',
        'is_prepack',
        'is_obsequio',
        'ultima_fecha_compra',
        'precio_corriente',
        'porcentaje_costo',
        'suma_costo_caja',
        'cantidad_caja',
        'otro_impuesto',
        'fe_type_item_identification_id',
    ];

    protected $primaryKey = 'producto_id';
    public $timestamps = false;

    public function allTablesRelations(){
        return array(
            'ubicacion_fisica',
            'grupo',
            'impuesto',
            'tipo_producto',
            'clasificacion',
            'componentes',
            'contenido_interno',
            'precios',
            'codigos_barra',
            'paquetes'
        );
    }

    public function ubicacion_fisica()
    {
        return $this->belongsTo(UbicacionFisicaElo::class,'producto_ubicacion_fisica');
    }

    public function grupo()
    {
        return $this->belongsTo(GrupoElo::class,'produto_grupo');
    }

    public function impuesto()
    {
        return $this->belongsTo(ImpuestoElo::class,'producto_impuesto');
    }

    public function tipo_producto()
    {
        return $this->belongsTo(TipoProductoElo::class,'producto_tipo');
    }

    public function clasificacion()
    {
        return $this->belongsTo(ClasificacionElo::class,'producto_clasificacion');
    }

    public function componentes()
    {
        return $this->belongsToMany(ComponentesElo::class,'producto_has_componente',
            'producto_id','componente_id');
    }

    public function contenido_interno()
    {
        return $this->belongsToMany(UnidadesElo::class,'unidades_has_producto',
            'producto_id','id_unidad')->withPivot('unidades','stock_minimo','stock_maximo','costo');
    }

    public function precios()
    {
        return $this->hasMany(UnidadesHasPrecioElo::class,'id_producto','producto_id');
    }

    public function codigos_barra()
    {
        return $this->hasMany(ProductoCodigoBarraElo::class,'producto_id','producto_id');
    }

    public function paquetes()
    {
        return $this->hasMany(PaqueteHasProdElo::class,'paquete_id','producto_id');
    }

}