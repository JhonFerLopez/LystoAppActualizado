<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class control_ambiental_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_all()
    {
        $this->db->select('control_ambiental.*, usuario.username, usuario.nombre');
        $this->db->from('control_ambiental');
        $this->db->join('usuario', 'control_ambiental.usuario_crea=usuario.nUsuCodigo');
        $query = $this->db->get();
        return $query->result();
    }


    function get_only_control($where = array(''))
    {
        $this->db->select('control_ambiental.*, usuario.username, usuario.nombre');
        $this->db->from('control_ambiental');
        $this->db->join('usuario', 'control_ambiental.usuario_crea=usuario.nUsuCodigo');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->row_array();
    }


    function set_control($control)
    {

        $validar_mes = sizeof($this->get_only_control(array('periodo' => $control['periodo'])));

        if ($validar_mes < 1) {
            $this->db->trans_start();
            $this->db->insert('control_ambiental', $control);
            $this->db->trans_complete();

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if ($this->db->trans_status() === FALSE)
                return FALSE;
            else

                return TRUE;
        } else {
            return "Para este mes ya existe un registro";

        }
    }

    function update_control($control)
    {

        $buscar = $this->get_only_control(array('periodo' => $control['periodo']));
        $validar_mes = sizeof($buscar);
        if ($validar_mes < 1 or ($validar_mes > 0 and ($buscar['control_ambiental_id'] == $control['control_ambiental_id']))) {
            $this->db->trans_start();
            $this->db->where('control_ambiental_id', $control['control_ambiental_id']);
            $this->db->update('control_ambiental', $control);
            $this->db->trans_complete();

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if ($this->db->trans_status() === FALSE)
                return FALSE;
            else
                return TRUE;
        } else {
            return "Para este mes ya existe un registro";

        }
    }

    function getOnlyDetalle($where = array(''))
    {
        $this->db->select('*');
        $this->db->from('control_ambiental_detalle');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }

    function getAll_control_amb_notific($where = array('')){
        $this->db->select('*');
        $this->db->from('control_ambiental_notific');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }

    function getWithDetail($where = array(''))
    {
        $this->db->select('*');
        $this->db->from('control_ambiental');
        $this->db->join('control_ambiental_detalle', 'control_ambiental_detalle.control_id=control_ambiental.control_ambiental_id');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result();
    }

    function guardardetalle($control, $post)
    {

        //obtengo el numero de dias que tiene el mes del control ambiental
        $número = cal_days_in_month(CAL_GREGORIAN,
            date("m", strtotime($control['periodo'])), date("Y", strtotime($control['periodo'])));
        $this->db->trans_start();
        for ($i = 1; $i <= $número; $i++) {
            $datos = array();
            $datos['dia'] = str_pad($i, 2, "0", STR_PAD_LEFT);
            $datos['control_id'] = $control['control_ambiental_id'];
            $datos['humedad_relat_am'] = null;
            $datos['temp_ambiental_am'] = null;
            $datos['humedad_relat_pm'] = null;
            $datos['temp_ambiental_pm'] = null;
            if (isset($post['input_humedadAM' . $i]) && $post['input_humedadAM' . $i] != '') {
                $datos['humedad_relat_am'] = $post['input_humedadAM' . $i];
            }
            if (isset($post['input_tempambientalAM' . $i]) && $post['input_tempambientalAM' . $i] != '') {
                $datos['temp_ambiental_am'] = $post['input_tempambientalAM' . $i];
            }
			if (isset($post['input_cadenafrioAM' . $i]) && $post['input_cadenafrioAM' . $i] != '') {
				$datos['cadena_frio_am'] = $post['input_cadenafrioAM' . $i];
			}

            if (isset($post['input_humedadPM' . $i]) && $post['input_humedadPM' . $i] != '') {
                $datos['humedad_relat_pm'] = $post['input_humedadPM' . $i];
            }
            if (isset($post['input_tempambientalPM' . $i]) && $post['input_tempambientalPM' . $i] != '') {
                $datos['temp_ambiental_pm'] = $post['input_tempambientalPM' . $i];
            }
			if (isset($post['input_cadenafrioPM' . $i]) && $post['input_cadenafrioPM' . $i] != '') {
				$datos['cadena_frio_pm'] = $post['input_cadenafrioPM' . $i];
			}

            $where = array('control_id' => $control['control_ambiental_id'], 'dia' => $i);
            $existe = $this->getOnlyDetalle($where);


            if (count($existe) > 0) {
                $this->db->where($where);
                $this->db->update('control_ambiental_detalle', $datos);
            } else {
                $this->db->insert('control_ambiental_detalle', $datos);
            }
        }

        $this->db->trans_complete();
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }


    function gethrsnot()
    {
        $this->db->select('*');
        $this->db->from('control_ambiental_hrsnot');
        $query = $this->db->get();
        return $query->result();
    }

    function guardarhrsNotif($post){
        //obtengo el numero de dias que tiene el mes del control ambiental
        $horasnot = $this->gethrsnot();
        $this->db->trans_start();


            if(isset($post['hora_am']) &&  $post['hora_am']!=''){
                $datos = array();
                $datos['hora'] = str_pad($post['hora_am'], 2, "0", STR_PAD_LEFT);
                $this->db->where('alias', 'humedad_relat_am');
                $this->db->update('control_ambiental_hrsnot', $datos);

                $this->db->where('alias', 'temp_ambiental_am');
                $this->db->update('control_ambiental_hrsnot', $datos);

				$this->db->where('alias', 'cadena_frio_am');
				$this->db->update('control_ambiental_hrsnot', $datos);
            }

        if(isset($post['hora_pm']) &&  $post['hora_pm']!=''){
            $datos = array();
            $datos['hora'] = str_pad($post['hora_pm'], 2, "0", STR_PAD_LEFT);
            $this->db->where('alias', 'humedad_relat_pm');
            $this->db->update('control_ambiental_hrsnot', $datos);

            $this->db->where('alias', 'temp_ambiental_pm');
            $this->db->update('control_ambiental_hrsnot', $datos);

			$this->db->where('alias', 'cadena_frio_pm');
			$this->db->update('control_ambiental_hrsnot', $datos);
        }

        if(isset($post['minuto_am']) &&  $post['minuto_am']!=''){
            $datos = array();
            $datos['minutos'] = str_pad($post['minuto_am'], 2, "0", STR_PAD_LEFT);
            $this->db->where('alias', 'humedad_relat_am');
            $this->db->update('control_ambiental_hrsnot', $datos);

            $this->db->where('alias', 'temp_ambiental_am');
            $this->db->update('control_ambiental_hrsnot', $datos);

			$this->db->where('alias', 'cadena_frio_am');
			$this->db->update('control_ambiental_hrsnot', $datos);
        }

        if(isset($post['minuto_pm']) &&  $post['minuto_pm']!=''){
            $datos = array();
            $datos['minutos'] = str_pad($post['minuto_pm'], 2, "0", STR_PAD_LEFT);
            $this->db->where('alias', 'humedad_relat_pm');
            $this->db->update('control_ambiental_hrsnot', $datos);

            $this->db->where('alias', 'temp_ambiental_pm');
            $this->db->update('control_ambiental_hrsnot', $datos);

			$this->db->where('alias', 'cadena_frio_pm');
			$this->db->update('control_ambiental_hrsnot', $datos);
        }



        $where='DATE(fecha) = DATE(NOW())';
        $this->delNotif($where);


        $this->db->trans_complete();

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;

    }

    //trae el valor de un item especifico
    function getEspecifHrsnot($where = array(''))
    {
        $this->db->select('*');
        $this->db->from('control_ambiental_hrsnot');
        $this->db->where($where);
        $query = $this->db->get();
        return $query->row_array();
    }

    //guarda las cerradas de las notificaciones
    function guardarNotif($datos){

        $this->db->trans_start();
        $this->db->insert('control_ambiental_notific', $datos);
        $this->db->trans_complete();
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }

    //guarda las cerradas de las notificaciones
    function delNotif($where){

        $this->db->trans_start();
        $this->db->delete('control_ambiental_notific', $where);
        $this->db->trans_complete();
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }

}
