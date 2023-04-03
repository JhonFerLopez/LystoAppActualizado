<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class tipo_devolucion_model extends CI_Model
{

    private $table = 'tipo_devolucion';
    private $nombre = 'tipo_devolucion_nombre';
    private $id = 'tipo_devolucion_id';

    private $deleted = 'deleted_at';

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function get_all($uuid = '')
    {
        $FACT_E_API_DESTINO = $this->session->userdata('FACT_E_API_DESTINO');
        if(strlen($uuid) > 10 && $FACT_E_API_DESTINO  == 'SOENAC') {
            $base_url = API_ENDPOINT;
            $url = $base_url  . "/api/ubl2.1/listings?tables=correction_concepts";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 200);
            $data_result = curl_exec($ch);
            if (curl_errno($ch) or curl_error($ch)) {
                $result = array('error' => curl_error($ch));
            } else {
                $transaction = json_decode($data_result, TRUE);
                $array = array();
                foreach($transaction['correction_concepts'] as $value){
                    if($value['type_document_id'] == 5){
                        array_push($array, array('tipo_devolucion_id' => $value['id'], 'tipo_devolucion_nombre' => $value['name']));
                    }   
                }
                curl_close($ch);
                $result = $array;
            }
        } else {
            $query = $this->db->where($this->deleted, null);
            $this->db->order_by($this->nombre, 'asc');
            $query = $this->db->get($this->table);
            $result = $query->result_array();
        }
        return $result;
    }

    function get_by($array)
    {
        $this->db->where($array);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    function set($data)
    {
        $validar_nombre = sizeof($this->get_by(array($this->nombre => $data[$this->nombre], $this->deleted => NULL)));
        if ($validar_nombre < 1) {
            $this->db->trans_start();
            $this->db->insert($this->table, $data);
            $insert_id = $this->db->insert_id();
            $this->db->trans_complete();
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if ($this->db->trans_status() === FALSE)
                return FALSE;
            else
                return $insert_id;
        } else {
            return NOMBRE_EXISTE;
        }
    }

    function update($grupo)
    {
        $produc_exite = $this->get_by(array($this->nombre => $grupo[$this->nombre], $this->deleted => null));
        $validar_nombre = sizeof($produc_exite);
        if ($validar_nombre < 1 or ($validar_nombre > 0 and ($produc_exite [$this->id] == $grupo [$this->id]))) {
            $this->db->trans_start();
            $this->db->where($this->id, $grupo[$this->id]);
            $this->db->update($this->table, $grupo);
            $this->db->trans_complete();

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if ($this->db->trans_status() === FALSE)
                return FALSE;
            else
                return TRUE;

        } else {
            return NOMBRE_EXISTE;

        }
    }


    function softDelete($data)
    {


        $this->db->trans_start();
        $this->db->where($this->id, $data[$this->id]);
        $this->db->update($this->table, $data);

        /* $modificar = array($this->table => NULL);
         $this->db->where($this->table , $data[$this->id]);
         $this->db->update('devolucion', $modificar);
 */

        $this->db->trans_complete();

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return TRUE;
    }

}
