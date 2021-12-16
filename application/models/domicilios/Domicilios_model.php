<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class domicilios_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function getOnlyDomicilios()
    {
        $this->db->select('*');
        $query= $this->db->get('domicilios');
        return $query->result();
    }

    function getDomicilio($where=array(''))
    {
        $this->db->where($where);
        $query= $this->db->get('domicilios');
        return $query->row_array();
    }

    function getDomicilios($where=array(''))
    {
        $this->db->select('domicilios.*, u.nUsuCodigo,u.nombre,u.identificacion,
        usua.nUsuCodigo AS usuasig_id,usua.nombre AS usuasig_nombre,usua.identificacion AS usuasig_identificacion,
          cliente.nombres, cliente.apellidos,cliente.longitud, cliente.latitud, cliente.celular, cliente.telefono,
          cliente.direccion, cliente.string_promedio,venta.total, documento_venta.documento_Numero, resolucion_dian.resolucion_prefijo');
        $this->db->from("domicilios");
        $this->db->join('usuario u','u.nUsuCodigo=domicilios.usuario_id','left');
        $this->db->join('usuario usua','usua.nUsuCodigo=domicilios.usuario_asigna','left');
        $this->db->join('cliente','cliente.id_cliente=domicilios.cliente_id');
        $this->db->join('venta','venta.venta_id=domicilios.domicilio_id');
        $this->db->join('documento_venta', 'documento_venta.id_venta=venta.venta_id', 'left');
        $this->db->join('resolucion_dian', 'resolucion_dian.resolucion_id=documento_venta.id_resolucion', 'left');
        $this->db->where($where);
        $this->db->order_by("fecha_created", "desc");
        $query= $this->db->get();


        return $query->result();
    }


    function getDomicilioFull($where=array(''))
    {
        $this->db->select('domicilios.*, u.nUsuCodigo,u.nombre,u.identificacion,
        usua.nUsuCodigo AS usuasig_id,usua.nombre AS usuasig_nombre,usua.identificacion AS usuasig_identificacion,
          cliente.nombres, cliente.apellidos,cliente.longitud, cliente.latitud, cliente.celular, cliente.telefono,
          cliente.direccion, cliente.string_promedio,venta.total, documento_venta.documento_Numero, resolucion_dian.resolucion_prefijo');
        $this->db->from("domicilios");
        $this->db->join('usuario u','u.nUsuCodigo=domicilios.usuario_id','left');
        $this->db->join('usuario usua','usua.nUsuCodigo=domicilios.usuario_asigna','left');
        $this->db->join('cliente','cliente.id_cliente=domicilios.cliente_id');
        $this->db->join('venta','venta.venta_id=domicilios.domicilio_id');
        $this->db->join('documento_venta', 'documento_venta.id_venta=venta.venta_id', 'left');
        $this->db->join('resolucion_dian', 'resolucion_dian.resolucion_id=documento_venta.id_resolucion', 'left');
        $this->db->where($where);
        $this->db->order_by("fecha_created", "desc");
        $query= $this->db->get();


        return $query->row();
    }

    function onlyLastDomicilio($where=array(''))
    {
        //esto busca solo el ultimo domicilio con una condicion
        $this->db->select('*');
        $this->db->where($where);
        $this->db->order_by("fecha_created", "desc");
        $this->db->limit(1);
        $query= $this->db->get('domicilios');
        return $query->row_array();
    }


    function onlyLastDomicilioHist($where=array(''))
    {
        //esto busca solo el ultimo historial que tenga con una condicion
        $this->db->select('*');
        $this->db->where($where);
        $this->db->order_by("fecha", "desc");
        $this->db->limit(1);
        $query= $this->db->get('domicilio_historial');
        return $query->row_array();
    }
    function saveDomicilios($datos)
    {
        $this->db->trans_start();
        $this->db->insert('domicilios', $datos);
        $domicilio_id = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $domicilio_id;
        }
    }

    function updateDomicilios($where,$datos)
    {
        $this->db->trans_start();
        $this->db->where($where);
        $this->db->update('domicilios', $datos);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    function getPromedio($where=array(''))
    {
        $this->db->where($where);
        $query= $this->db->get('domiciliario_promedio');
        return $query->row_array();
    }

    function savePromedioDom($datos)
    {
        $this->db->trans_start();
        $this->db->insert('domiciliario_promedio', $datos);
        $domicilio_id = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $domicilio_id;
        }
    }

    function updatePromedioDom($where,$datos)
    {
        $this->db->trans_start();
        $this->db->where($where);
        $this->db->update('domiciliario_promedio', $datos);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    function getHistDom($where=array(''))
    {
        //esto busca todo el historial que tenga el domicilio
        $this->db->select('domicilio_historial.*,usuario.username, usuario.nombre, usuario.identificacion');
        $this->db->from('domicilio_historial');
        $this->db->join('usuario','usuario.nUsuCodigo=domicilio_historial.usuario','left');
        $this->db->where($where);
        $this->db->order_by("fecha", "desc");
        $query= $this->db->get();
        return $query->result();
    }

}