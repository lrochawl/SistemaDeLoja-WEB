<?php
class ConfigProduto_model extends CI_Model
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
        $this->db->select($fields);
        $this->db->from($table);
       // $this->db->order_by('idMarca', 'desc');
      //  $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        
        $result =  !$one  ? $query->result() : $query->row();
        return $result;
    }
    public function getMedida($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select('medidas.*,medidas_sistema.*');
        $this->db->from($table);
        $this->db->join('medidas_sistema', 'medidas.idMedidaSistema = medidas_sistema.idMedidaSistema' );
      //  $this->db->order_by('idMedida', 'desc');
       // $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        
        $resultMedida =  !$one  ? $query->result() : $query->row();
        return $resultMedida;
    }
    public function getCategoria($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->order_by('idCategoria', 'desc');
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        
        $resultCategoria =  !$one  ? $query->result() : $query->row();
        return $resultCategoria;
    }
    public function getTipoProduto($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->order_by('idTipo', 'desc');
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        
        $resultTipo =  !$one  ? $query->result() : $query->row();
        return $resultTipo;
    }
    public function getMarca($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select('*');
        $this->db->from('marcas');
        $this->db->order_by('idMarca', 'desc'); 
      //  $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        
        $resultTipo =  !$one  ? $query->result() : $query->row();
        return $resultTipo;
    }

    public function getTipoProdutoID($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select('*');
        $this->db->from('produtos');
        $this->db->join('marcas', 'produtos.marcaId = marcas.idMarca' );
        $this->db->join('tipo_produtos', 'produtos.tipoId = tipo_produtos.idTipo' );
        $this->db->order_by('idProdutos', 'desc'); 
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }
         
        $query = $this->db->get();
        
        $resultTipo =  !$one  ? $query->result() : $query->row();
        return $resultTipo;
    }
    public function getById($id)
    {
        $this->db->where('idMarca', $id);
        $this->db->limit(1);
        return $this->db->get('marcas')->row();
    }
    public function autoCompleteMarca($q)
    {
        $this->db->select('*');
        $this->db->limit(5);
        $this->db->like('marca', $q);
        $this->db->or_like('sigla', $q);
        $query = $this->db->get('marcas');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = ['label'=>$row['marca'],'marca'=>$row['marca'],'id'=>$row['idMarca'],'siglaMarca'=>$row['sigla'], 'statusMarca'=>$row['status']];
            }
            echo json_encode($row_set);
        }else {
            $row_set[] = ['label'=> 'Adicionar => '. $q, 'id' => null, 'marca'=>$q];
            echo json_encode($row_set);
        }
    }
 
    public function autoCompleteMedida($q)
    {
        $this->db->select('*');
        $this->db->limit(5);
        $this->db->like('descricaoMedida', $q);
        $this->db->or_like('sigla', $q);
        $query = $this->db->get('medidas');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = ['label'=>strtoupper($row['descricaoMedida']).' | sigla: '.strtoupper($row['sigla']),'id'=>$row['idMedida'],'descricaoMedida'=>strtoupper($row['descricaoMedida']),'siglaMedida'=>$row['sigla'],'idMedidaSistema'=>$row['idMedidaSistema'],'multiplicador'=>$row['multiplicador'],'statusMedida'=>$row['status'],'bloqueio'=>$row['bloqueio']];
            }
            echo json_encode($row_set);
        } else {
            $row_set[] = ['label'=> 'Adicionar => '. $q, 'id' => null, 'medida'=>strtoupper($q)];
            echo json_encode($row_set); 
        }
    }
    public function autoCompleteCategoria($q)
    {
        $this->db->select('*');
        $this->db->limit(5);
        $this->db->like('categoria', $q);
        $this->db->or_like('sigla', $q);
        $query = $this->db->get('categorias');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = ['label'=>strtoupper($row['categoria']).' | sigla: '.strtoupper($row['sigla']),'id'=>$row['idCategoria'],'descricaoCategoria'=>strtoupper($row['categoria']),'siglaCategoria'=>strtoupper($row['sigla']),'statusCategoria'=>$row['status']];
            }
            echo json_encode($row_set);
        } else {
            $row_set[] = ['label'=> 'Adicionar => '. $q, 'id' => null, 'categoria'=>strtoupper($q)];
            echo json_encode($row_set); 
        }
    } 
    public function autoCompleteTipoProduto($q)
    {
        $this->db->select('marcas.*,tipo_produtos.*');
        $this->db->from('tipo_produtos');
        $this->db->join('marcas', 'marcas.idMarca = tipo_produtos.idMarca' );
        $this->db->limit(5);
        $this->db->like('descricaoProduto', $q);
        $this->db->or_like('marca', $q);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = ['label'=>$row['descricaoProduto'].' =>Marca: '.$row['marca'],'tipoProduto'=>$row['descricaoProduto'],'id'=>$row['idTipo'],'idMarcaProduto'=>$row['idMarca'],'marcaProduto'=>$row['marca'],'siglaProduto'=>$row['sigla'],'statusProduto'=>$row['status']];
            }
            echo json_encode($row_set);
        }else {
            $row_set[] = ['label'=> 'Adicionar => '. $q, 'id' => null, 'produto'=>$q];
            echo json_encode($row_set);
        }
    }

    public function autoCompleteCliente($q)
    {
        $this->db->select('*');
        $this->db->limit(5);
        $this->db->like('nomeCliente', $q);
        $query = $this->db->get('clientes');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = ['label'=>$row['nomeCliente'].' | Telefone: '.$row['telefone'],'id'=>$row['idClientes']];
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

    public function updateMarcas($marca, $marcaMod )
    {
        $sql = "UPDATE marcas set marca = ? WHERE idMarca = ?";
        return $this->db->query($sql, [$marcaMod, $marca]);
    }
}
