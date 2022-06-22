<?php
class Produtos_model extends CI_Model
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
        $this->db->order_by('idProdutos', 'desc');
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        
        $result =  !$one  ? $query->result() : $query->row();
        return $result;
    }
    public function getMedidaSistema($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->order_by('idMedidaSistema', 'desc');
        $this->db->limit($perpage, $start);
        if ($where) {
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        
        $result =  !$one  ? $query->result() : $query->row();
        return $result;
    }
    public function getUnidadeProdutoID($id)
    {
        $this->db->select('produtos.*,medidas.*,marcas.marca, categorias.categoria, tipo_produtos.descricaoProduto, tipo_produtos.idMarca,tipo_produtos.idTipo, medidas_sistema.*, notas_fiscais.idNotaFiscal, notas_fiscais.notaFiscal');
        $this->db->from('produtos');
        $this->db->join('medidas', 'produtos.idUnidade = medidas.idMedida' );
        $this->db->join('marcas', 'produtos.marcaId = marcas.idMarca' );
        $this->db->join('categorias', 'produtos.categoriaId = categorias.idCategoria' );
        $this->db->join('tipo_produtos', 'produtos.tipoId = tipo_produtos.idTipo' );
        $this->db->join('medidas_sistema', 'medidas.idMedidaSistema = medidas_sistema.idMedidaSistema' );
        $this->db->join('notas_fiscais', 'produtos.notaFiscalId = notas_fiscais.notaFiscal', 'left' );
        $this->db->where('produtos.idProdutos', $id);
        $this->db->limit(1);
        return $this->db->get()->row();
    }
    public function getUnidadeProduto($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select('produtos.*,medidas.*,marcas.marca, categorias.categoria, tipo_produtos.descricaoProduto, tipo_produtos.idMarca,tipo_produtos.idTipo, medidas_sistema.*');
        $this->db->from('produtos');
        $this->db->limit($perpage, $start);
        $this->db->join('medidas', 'produtos.idUnidade = medidas.idMedida' );
        $this->db->join('marcas', 'produtos.marcaId = marcas.idMarca' );
        $this->db->join('categorias', 'produtos.categoriaId = categorias.idCategoria' );
        $this->db->join('tipo_produtos', 'produtos.tipoId = tipo_produtos.idTipo' );
        $this->db->join('medidas_sistema', 'medidas.idMedidaSistema = medidas_sistema.idMedidaSistema' );
      
        $this->db->order_by('idProdutos', 'desc');

        if ($where) {
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        
        $result =  !$one  ? $query->result() : $query->row();
        return $result;
    }
    public function getNotasFiscais($table, $fields, $where = '', $perpage = 0, $start = 0, $one = false, $array = 'array')
    {
        $this->db->select('*');
        $this->db->from('notas_fiscais');
   
       $this->db->order_by('notaFiscal', 'desc');

        if ($where) {
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        
        $result =  !$one  ? $query->result() : $query->row();
        return $result;
    }
    public function autoCompleteProduto($q)
    {
        $this->db->select('*');
        $this->db->limit(5);
        $this->db->like('descricao', $q);
        $this->db->or_like('codDeBarra', $q);
        $query = $this->db->get('produtos');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = ['label'=>$row['codDeBarra'].' | descricao '.$row['descricao'].' | Estoque: '.$row['estoque'],'estoque'=>$row['estoque'],'id'=>$row['idProdutos'],'codigo'=>$row['codDeBarra'],'preco'=>$row['precoVenda']];
            }
            echo json_encode($row_set);
        }else {
            $row_set['0'] = ['label'=> 'Adicionar => '. $q, 'id' => '1', 'codigo'=>$q]; 
            echo json_encode($row_set);
        }
    }
    public function autoCompleteNotaFiscal($q)
    {
        $this->db->select('*');
        $this->db->join('clientes', 'clientes.idClientes = notas_fiscais.fornecedorId');
        $this->db->limit(5);
        $this->db->like('notaFiscal', $q);
        $this->db->or_like('notaFiscal', $q);
        $query = $this->db->get('notas_fiscais');
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $row_set[] = ['label'=>'Nº '.$row['notaFiscal'].' | fornecedor '.$row['nomeCliente'],'id'=>$row['idNotaFiscal'],'notaFiscal'=>$row['notaFiscal']];
            }
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
    public function getById($id)
    {
        
        $this->db->where('idProdutos', $id);
        $this->db->limit(1);
        return $this->db->get('produtos')->row();
    }
    public function getByMedidaId($id)
    {
        $this->db->join('medidas', 'produtos.idUnidade = medidas.idMedida' );
        $this->db->join('categorias', 'produtos.categoriaId = categorias.idCategoria' );
        $this->db->where('idProdutos', $id);
        $this->db->limit(1);
        return $this->db->get('produtos')->row();
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

    public function updateEstoque($produto, $quantidade, $operacao = '-')
    {
        $sql = "UPDATE produtos set estoque = estoque $operacao ? WHERE idProdutos = ?";
        return $this->db->query($sql, [$quantidade, $produto]);
    }
}
