<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class impuestos_model extends CI_Model
{

    private $table = 'impuestos';

    function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    function get_fe_listings_soenac($list = 'taxes', $return = 'OBJECT')
    {
        $base_url = API_ENDPOINT;
        $url = $base_url  . "/api/ubl2.1/listings?tables=".$list;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);
        $data_result = curl_exec($ch);
        if (curl_errno($ch) or curl_error($ch)) {
            $result = array('error' => curl_error($ch));
        } else {
            $transaction = json_decode($data_result, TRUE);
            curl_close($ch);
            $result = ($return == 'OBJECT') ? json_decode(json_encode($transaction[$list], JSON_FORCE_OBJECT)) : $transaction[$list] ; 
        }
        return $result;
    }

    //FE === FACTURACION ELECTRONICA
    function get_fe_impuestos()
    {
        $this->db2 = $this->load->database('second', TRUE);
        $this->db2->where('deleted_at', NULL);
        $query = $this->db2->get('taxes');
        return $query->result();
    }

    function get_fe_payment_forms()
    {
        $this->db2 = $this->load->database('second', TRUE);
        $this->db2->where('deleted_at', NULL);
        $query = $this->db2->get('payment_forms');
        return $query->result();
    }
  function get_fepayment_methods()
    {
		$this->db2 = $this->load->database('second', TRUE);
        $query = $this->db2->get('payment_methods');
        return $query->result();
    }

    function get_fe_countries()
    {
		$this->db2 = $this->load->database('second', TRUE);
        $query = $this->db2->get('countries');
        return $query->result();
    }

    function get_fe_languages()
    {
		$this->db2 = $this->load->database('second', TRUE);
        $query = $this->db2->get('languages');
        return $query->result();
    }

    function get_fe_typeregime()
    {
        $this->db2 = $this->load->database('second', TRUE);
        $this->db2->where('deleted_at', NULL);
        $query = $this->db2->get('type_regimes');
        return $query->result();
    }

    function get_fe_typedocument()
    {
        $this->db2 = $this->load->database('second', TRUE);
        $this->db2->where('deleted_at', NULL);
        $query = $this->db2->get('type_documents');
        return $query->result();
    }

    function get_fe_typecurrency()
    {
        $this->db2 = $this->load->database('second', TRUE);
        $this->db2->where('deleted_at', NULL);
        $query = $this->db2->get('type_currencies');
        return $query->result();
    }

    function get_fe_municipalities()
    {
        $this->db2 = $this->load->database('second', TRUE);
        $this->db2->where('deleted_at', NULL);
        $query = $this->db2->get('municipalities');
        return $query->result();
    }

    function get_fe_typeliabilities()
    {
		$this->db2 = $this->load->database('second', TRUE);
        $this->db2->where('deleted_at', NULL);
        $query =  $this->db2->get('type_liabilities');
        return $query->result();
    }

    function get_fe_typeoperation()
    {
        $this->db2 = $this->load->database('second', TRUE);
        $this->db2->where('deleted_at', NULL);
        $query = $this->db2->get('type_operations');
        return $query->result();
    }

    function get_fe_typeenviroment()
    {
		$this->db2 = $this->load->database('second', TRUE);
        $query = $this->db2->get('type_environments');
        return $query->result();
    }

    function get_fe_typeorganizations()
    {
		$this->db2 = $this->load->database('second', TRUE);
        $query = $this->db2->get('type_organizations');
        return $query->result();
    }

    function get_fe_typedocumentidentifications()
    {
		$this->db2 = $this->load->database('second', TRUE);
        $query = $this->db2->get('type_document_identifications');
        return $query->result();
    }
    function get_fe_taxdetails()
    {
        $this->db2 = $this->load->database('second', TRUE);
        $this->db2->where('deleted_at', NULL);
        $query = $this->db2->get('tax_details');
        return $query->result();
    }


    function get_impuestos()
    {

        $this->db->select($this->table . '.*, ' . $this->table . '.nombre_impuesto as text, ' . $this->table . '.id_impuesto as id');
        $this->db->from($this->table);
        $this->db->where('estatus_impuesto', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_by($campo, $valor)
    {
        $this->db->where($campo, $valor);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    function set_impuestos($grupo)
    {

        $nombre = $this->input->post('nombre');
        $validar_nombre=$this->get_by('nombre_impuesto', $nombre);

        if ($validar_nombre ==NULL) {
            $this->db->trans_start();
            $this->db->insert($this->table, $grupo);

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


    function crear_impuesto($datos)
    {

        $this->db->trans_start();
        $this->db->insert($this->table, $datos);
        $id = $this->db->insert_id();
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE)
            return FALSE;
        else
            return $id;

    }

    function update_impuestos($grupo)
    {

        $produc_exite = $this->get_by('nombre_impuesto', $grupo['nombre_impuesto']);

        if ($produc_exite==NULL or ($produc_exite !=NULL  and ($produc_exite ['id_impuesto'] == $grupo ['id_impuesto']))) {
            $this->db->trans_start();
            $this->db->where('id_impuesto', $grupo['id_impuesto']);
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


}
