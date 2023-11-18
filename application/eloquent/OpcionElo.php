<?php

use \Illuminate\Database\Eloquent\Model as Eloquent;

class   OpcionElo extends Eloquent {

    protected $table = "opcion";
    protected $fillable = [
        'nOpcion',
        'nOpcionClase',
        'cOpcionNombre',
        'cOpcionDescripcion',
        'is_to_show_some_value' //Esta columna, indica, que no es para mostrar
            // opciones en el menú sino para mostrar ciertos datos dentro de los módulos al que esté asociada la fila
    ];
    public $timestamps = false;
    protected $primaryKey = 'nOpcion';



}