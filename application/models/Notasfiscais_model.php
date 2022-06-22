<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Notasfiscais_model extends CI_Model
{

    /**
     * author: Ramon Silva
     * email: silva018-mg@yahoo.com.br
     *
     */
    
    public function get($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->join('clientes', 'notas_fiscais.fornecedorId = clientes.idClientes' );
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        
        $result =  !$one  ? $query->result() : $query->row();
        return $result;
    }


    public function getById($id)
    {
        $this->db->where('idNotaFiscal', $id);
        $this->db->limit(1);
        return $this->db->get('notas_fiscais')->row();
    }

    public function autoCompleteFornecedor($q)
    {
        $this->db->select('*');
        $this->db->limit(5);
        $this->db->like('nomeCliente', $q);
        $this->db->where('fornecedor', '1');
        $query = $this->db->get('clientes');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = ['label'=>'Fornecedor: '.$row['nomeCliente'].' | Telefone: '.$row['telefone'],'id'=>$row['idClientes']];
            }
            echo json_encode($row_set);
        } else {
            $row_set[] = ['label'=> 'Adicionar cliente...', 'id' => null];
            echo json_encode($row_set);
        }
    }

    public function add($table, $data)
    {
        $this->db->insert($table, $data);
        if ($this->db->affected_rows() == '1') {
            return true;
        }
        
        return false;
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
    
    public function count($table)
    {
        return $this->db->count_all($table);
    }

    public function search($pesquisa, $de, $ate)
    {
        if ($pesquisa != null) {
            $this->db->like('notas_fiscais', $pesquisa);
        }

        if ($de != null) {
            $this->db->where('cadastro >=', $de);
            $this->db->where('cadastro <=', $ate);
        }
        $this->db->limit(10);
        return $this->db->get('notas_fiscais')->result();
    }
}

/* End of file arquivos_model.php */
/* Location: ./application/models/arquivos_model.php */
