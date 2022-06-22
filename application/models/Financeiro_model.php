<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Financeiro_model extends CI_Model
{

    /**
     * author: Ramon Silva
     * email: silva018-mg@yahoo.com.br
     *
     */

    public function __construct()
    {
        parent::__construct();
    }


    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select($fields.', usuarios.*');
        $this->db->from($table);
        $this->db->join('usuarios', 'usuarios.idUsuarios = usuarios_id', 'left');
        $this->db->order_by('data_vencimento', 'asc');
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }

        $query = $this->db->get();

        $result = !$one ? $query->result() : $query->row();

        return $result;
    }

    public function getTotals($where = '')
    {
        $this->db->select("
            SUM(case when tipo = 'despesa' then valor end) as despesas,
            SUM(case when tipo = 'receita' then valor end) as receitas,
            SUM(case when tipo = 'receita'  then valor_ajuste end) as descontos_receita,
            SUM(case when tipo = 'despesa'  then valor_ajuste end) as descontos_despesa,
            SUM(case when tipo = 'receita'  then valor_pago end) as valor_pago_receita,
            SUM(case when tipo = 'despesa'  then valor_pago end) as valor_pago_despesa,
            SUM(case when tipo = 'despesa' and baixado = '1' then valor end) as despesas_pagas,
            SUM(case when tipo = 'receita' and baixado = '1' then valor end) as receitas_pagas,
            SUM(case when tipo = 'receita' and baixado = '1'  then valor_pago end) as valor_pago_receitas_pagas,
            SUM(case when tipo = 'despesa' and baixado = '1'  then valor_pago end) as valor_pago_despesas_pagas,
        ");
        $this->db->from('lancamentos');

        if ($where) {
            $this->db->where($where);
        }

        return (array) $this->db->get()->row();
    }

    public function getEstatisticasFinanceiro()
    {
        $sql = "SELECT SUM(CASE WHEN baixado = 1 AND tipo = 'receita' THEN valor END) as total_receitas_pagas_sem_desconto,
                       SUM(CASE WHEN baixado = 1 AND tipo = 'despesa' THEN valor END) as total_valor_despesas_sem_desconto,
                       SUM(CASE WHEN baixado = 0 AND tipo = 'receita' THEN valor END) as total_valor_receitas_pendentes,
                       SUM(CASE WHEN baixado = 0 AND tipo = 'despesa' THEN valor END) as total_valor_despesas_pendentes,
                       SUM(CASE WHEN baixado = 1 AND tipo = 'receita' THEN valor_pago END) as total_valor_pago_receitas,
                       SUM(CASE WHEN baixado = 1 AND tipo = 'despesa' THEN valor_pago END) as total_valor_pago_despesas,
                       SUM(CASE WHEN baixado = 1 AND tipo = 'receita' THEN valor_ajuste END) as total_descontos_pagos_receita,
                       SUM(CASE WHEN baixado = 0 AND tipo = 'receita' THEN valor_ajuste END) as total_valor_descontos_receita_pendentes,
                       SUM(CASE WHEN baixado = 1 AND tipo = 'receita' AND valor_ajuste = 0 THEN valor END) as total_receitas_pagas_sem_desconto,
                       SUM(CASE WHEN baixado = 1 AND tipo = 'despesa' AND valor_ajuste = 0 THEN valor END) as total_pagas_despesas_sem_desconto
                       FROM lancamentos";

    
        return $this->db->query($sql)->row();    
    }

    public function getById($id)
    {
        $this->db->where('idClientes', $id);
        $this->db->limit(1);
        return $this->db->get('clientes')->row();
    }

    public function add($table, $data)
    {
        $this->db->insert($table, $data);
        if ($this->db->affected_rows() == '1') {
            return true;
        }

        return false;
    }

    function add1($table,$data1){
        $this->db->insert($table, $data1);         
        if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		
		return FALSE;       
    }

    public function edit($table, $data, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->update($table, $data);

        if ($this->db->affected_rows() >= 0) {
            return true;
        }

        return false;
    }

    public function delete($table, $fieldID, $ID)
    {
        $this->db->where($fieldID, $ID);
        $this->db->delete($table);
        if ($this->db->affected_rows() == '1') {
            return true;
        }

        return false;
    }

    public function count($table, $where)
    {
        $this->db->from($table);
        if ($where) {
            $this->db->where($where);
        }
        return $this->db->count_all_results();
    }

    public function autoCompleteClienteFornecedor($q)
    {
        $this->db->select('DISTINCT(cliente_fornecedor) as cliente_fornecedor');
        $this->db->limit(5);
        $this->db->like('cliente_fornecedor', $q);
        $query = $this->db->get('lancamentos');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = ['label' => $row['cliente_fornecedor'], 'id' => $row['cliente_fornecedor']];
            }
            echo json_encode($row_set);
        }
    }

    public function isEditable($id = null)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eProdutos')) {
            return false;
        }
        if ($lancamentos = $this->getById($id)) {
            $lancamentosT = (int)($lancamentos->status === "Faturado" || $lancamentos->status === "Cancelado" || $lancamentos->faturado == 1);
            if ($lancamentosT) {
                return $this->data['configuration']['control_editos'] == '1';
            }
        }
        return true;
    }

    public function autoCompleteClienteReceita($q)
    {
        $this->db->select('idClientes, nomeCliente');
        $this->db->limit(5);
        $this->db->like('nomeCliente', $q);
        $query = $this->db->get('clientes');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = ['label' => $row['nomeCliente'], 'id' => $row['idClientes']];
            }
            echo json_encode($row_set);
        }
    }
}
