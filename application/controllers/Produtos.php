<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Produtos extends MY_Controller
{

    /**
     * author: Ramon Silva
     * email: silva018-mg@yahoo.com.br
     *
     */

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('form');
        $this->load->model('produtos_model');
        $this->load->model('ConfigProduto_model');
        $this->data['menuProdutos'] = 'Produtos';
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function consultaProduto($urlID )
    {
      // $urlID = $_POST['urlID'];
        //echo $urlID;
            $url = 'https://api.cosmos.bluesoft.com.br/gtins/'.$urlID.'.json';
            $agent = 'Cosmos-API-Request';
            $headers = array(
            "Content-Type: application/json",
            "X-Cosmos-Token: SgteGyzmkZ_nRp45EbrcuQ"
            );

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_USERAGENT, $agent);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FAILONERROR, true);

            $data = curl_exec($curl);
            if ($data === false || $data == NULL) {
            $data = 0;
            echo $data;
            } else {
            $object = json_encode($data);

            echo $data;
            }
            
            curl_close($curl);

            
    }
    public function consultaTipoProduto( )
    {
        $data = $this->data['resultTipoProduto'] = $this->ConfigProduto_model->getTipoProduto('tipo_produtos', '*', '', $this->uri->segment(3));
   
            if ($data === false || $data == NULL) {
                $this->data['erro'] = 1;
            } else {
                $this->data['erro'] = 0;
            }
            echo json_encode($data);
           

            
    }
    public function consultaTipoProdutoID( )
    {
        $data = $this->data['resultTipoProdutoID'] = $this->ConfigProduto_model->getTipoProdutoID('tipo_produtos', '*', '', $this->uri->segment(3));
   
            if ($data === false || $data == NULL || $data == '') {
                $this->data['erro'] = 1;
            } else {
                $this->data['erro'] = 0;
            }
            echo json_encode($data);
           

            
    }

    public function autoCompleteProduto()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->produtos_model->autoCompleteProduto($q);
        }
    }

    public function autoCompleteMarca()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->ConfigProduto_model->autoCompleteMarca($q);
        }
    }
    public function autoCompleteMedida()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->ConfigProduto_model->autoCompleteMedida($q);
        }
    }

    public function autoCompleteTipoProduto()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->ConfigProduto_model->autoCompleteTipoProduto($q);
        }
    }
    public function autoCompleteCategoria()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->ConfigProduto_model->autoCompleteCategoria($q);
        }
    } 

    public function autoCompleteCliente()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->produtos_model->autoCompleteCliente($q);
        }
    }

    public function autoCompleteNotaFiscal()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->produtos_model->autoCompleteNotaFiscal($q);
        }
    }

    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar produtos.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $this->data['configuration']['base_url'] = site_url('produtos/gerenciar/');
        $this->data['configuration']['total_rows'] = $this->produtos_model->count('produtos');
       

        $this->pagination->initialize($this->data['configuration']);

      //  $this->data['results'] = $this->produtos_model->getUnidadeProduto('produtos', '*', '', $this->data['configuration']['per_page'], $this->uri->segment(3));
        $this->data['results'] = $this->produtos_model->getUnidadeProduto('produtos', '*', '', $this->data['configuration']['per_page'], $this->uri->segment(3));
        $this->data['m'] = $this->ConfigProduto_model->get('marcas', '*', '',  $this->uri->segment(3));
        $this->data['medidas'] = $this->ConfigProduto_model->getMedida('medidas', '*', '', $this->data['configuration']['per_page'], $this->uri->segment(3));
        $this->data['medidasSistema'] = $this->produtos_model->getMedidaSistema('medidas_sistema', '*', '', $this->data['configuration']['per_page'], $this->uri->segment(3));
            
        $this->data['view'] = 'produtos/produtos';
        return $this->layout();
    }

    public function adicionar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar produtos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $this->data['resultMarca'] = $this->ConfigProduto_model->getMarca('', '*', '',$this->uri->segment(3));
        $this->data['resultMedida'] = $this->ConfigProduto_model->getMedida('medidas', '*', '', $this->uri->segment(3));
        $this->data['resultCategoria'] = $this->ConfigProduto_model->getCategoria('categorias', '*', '', $this->uri->segment(3));
        
        if ($this->form_validation->run('produtos') == false) {
           // $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $precoCompra = $this->input->post('precoCompra');
            $precoCompra = str_replace(",", "", $precoCompra);
            $precoVenda = $this->input->post('precoVenda');
            $precoVenda = str_replace(",", "", $precoVenda);
            $data = [
                'codDeBarra' => set_value('codDeBarra'),
                'descricao' => set_value('descricao'),
                'categoriaId' => set_value('categoria'),
                'marcaId' => set_value('marca'),
                'notaFiscalId' => set_value('adNotaFiscal_id'),
                'tipoId' => set_value('complemento'),
                'caracteristicas' => set_value('caracteristicas'),
                'iDunidade' => set_value('unidade'),
                'precoCompra' => $precoCompra,
                'margemLucro' => set_value('margemLucro'),
                'precoVenda' => $precoVenda,
                'estoque' => set_value('estoque'),
                'estoqueMinimo' => set_value('estoqueMinimo'),
                'saida' => set_value('saida'),
                'observacao' => set_value('observacao'),
                'dataCompra' => set_value('dataCompra'),
                'dataVencimento' => set_value('dataVencimento'),
                'dataCadastro' => set_value('dataCadastro'),
                'entrada' => set_value('entrada'),
            ];

            if ($this->produtos_model->add('produtos', $data) == true) {
                $this->session->set_flashdata('success', 'Produto adicionado com sucesso!');
                log_info('Adicionou um produto');
                redirect(site_url('produtos/adicionar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Verifique o preenchimento de todos os campos.</p></div>';
            }
        }
        $this->data['view'] = 'produtos/adicionarProduto';
        return $this->layout();
    }

    public function editar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar produtos.');
            redirect(base_url());
        }
        $this->load->library('form_validation');
        $this->data['custom_error'] = '';
        $this->data['resultMarca'] = $this->ConfigProduto_model->getMarca('', '*', '',$this->uri->segment(3));
        $this->data['resultMedida'] = $this->ConfigProduto_model->getMedida('medidas', '*', '', $this->uri->segment(3));
        $this->data['resultCategoria'] = $this->ConfigProduto_model->getCategoria('categorias', '*', '', $this->uri->segment(3));
        $this->data['notasFiscais'] = $this->produtos_model->getNotasFiscais('notas_fiscais', '*', '', $this->uri->segment(3));
       

        if ($this->form_validation->run('produtos') == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $precoCompra = $this->input->post('precoCompra');
            $precoCompra = str_replace(",", "", $precoCompra);
            $precoVenda = $this->input->post('precoVenda');
            $precoVenda = str_replace(",", "", $precoVenda);
           

            $data = [
                'codDeBarra' => $this->input->post('codDeBarra'),
                'descricao' => $this->input->post('descricao'),
                'categoriaId' => $this->input->post('categoria'),
                'marcaId' => $this->input->post('marca'),
                'notaFiscalId' => $this->input->post('adNotaFiscal_id'),
                'tipoId' => $this->input->post('complemento'), 
                'caracteristicas' => $this->input->post('caracteristicas'),
                'idUnidade' => $this->input->post('unidade'),
                'precoCompra' => $this->input->post('precoCompra'),
                'margemLucro' => $this->input->post('margemLucro'),
                'precoVenda' => $precoVenda,
                'estoque' => $this->input->post('estoque'),
                'observacao' => $this->input->post('observacao'),
                'estoqueMinimo' => $this->input->post('estoqueMinimo'),
                'dataCompra' => $this->input->post('dataCompra'),
                'dataVencimento' => $this->input->post('dataVencimento'),
                'dataUpdate' => set_value('dataUpdate'),
                'saida' => set_value(1),
                'entrada' => set_value(1),
            ];

            if ($this->produtos_model->edit('produtos', $data, 'idProdutos', $this->input->post('idProdutos')) == true) {
                $this->session->set_flashdata('success', 'Produto editado com sucesso!');
                log_info('Alterou um produto. ID: ' . $this->input->post('idProdutos'));
                redirect(site_url('produtos/editar/') . $this->input->post('idProdutos'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Verifique o preenchimento de todos os campos</p></div>';
            }
        }
        
        $this->data['result'] = $this->produtos_model->getUnidadeProdutoID($this->uri->segment(3));
       
        $this->data['view'] = 'produtos/editarProduto';
        return $this->layout();
    }


    public function config_produto()
    {
        
        $this->data['menuConfiguracoes'] = 'Configurações para cadastro de produto';

       // $this->load->library('form_validation');
        $this->load->model('ConfigProduto_model');
   
        $this->data['custom_error'] = '';

       

       if($this->input->post('idMenu') == 'menu1') {
           
            $idMarca = $this->input->post('idMarca');

            $data = [
               
                'marca' => strtoupper($this->input->post('idMarca')),
                'sigla' => strtoupper($this->input->post('sigla')), 
                'status' => $this->input->post('status'), 
            ];
            $dataUpdate = [
                'idMarca'=>$this->input->post('idMarca'),
                'marca' => strtoupper($this->input->post('marca')),
                'sigla' => strtoupper($this->input->post('sigla')), 
                'status' => $this->input->post('status'), 
            ];
            if (($this->input->post('acao')=='salvar') && ($this->ConfigProduto_model->add('marcas', $data) == true)) {
                $this->session->set_flashdata('success', 'Cadastro Realizado com sucesso!');
            } elseif(($this->input->post('acao')=='update') && ($this->ConfigProduto_model->edit('marcas', $dataUpdate, 'idMarca', $idMarca) == true) ){
                $this->session->set_flashdata('success', 'Cadastro realizado com sucesso!');
            }else {
                $this->session->set_flashdata('error', 'Não foi possivel realizar cadastro.');
            }
        }elseif($this->input->post('idMenu') == 'menu2') {
            
            $idProduto = $this->input->post('idProduto');
            $data = [

                'descricaoProduto' => strtoupper($this->input->post('idProduto')),
                'idMarca' => $this->input->post('idMarcaProduto'),
                'status' =>$this->input->post('status'),
                
            ];
            $dataUpdate = [
                'descricaoProduto' => strtoupper($this->input->post('produto')),
                'status' =>$this->input->post('status'),
                'idMarca' => $this->input->post('idMarcaProduto'),                
            ];
        
            if (($this->input->post('acao')=='salvar') && ($this->ConfigProduto_model->add('tipo_produtos', $data) == true)) {
                $this->session->set_flashdata('success', 'Cadastro realizado!');
            } elseif(($this->input->post('acao')=='update') && ($this->ConfigProduto_model->edit('tipo_produtos', $dataUpdate, 'idTipo', $idProduto) == true)){
                $this->session->set_flashdata('success', 'Atualização realizada!');
            }else {
                $this->session->set_flashdata('error', 'Não foi possivel realizar cadastro.');
            }
        }elseif($this->input->post('idMenu') == 'menu3') {
           $idCategoria = $this->input->post('idCategoria');
            $data = [
                'categoria' => strtoupper($this->input->post('idCategoria')),
                'sigla' => strtoupper($this->input->post('siglaCategoria')),
                'status' => $this->input->post('statusCategoria'),
          
            ];
            $dataUpdate = [ 
                'categoria' =>strtoupper($this->input->post('categoria')),
                'sigla' => strtoupper($this->input->post('siglaCategoria')),
                'status' => $this->input->post('statusCategoria'),
            ];
           
            if (($this->input->post('acao')=='salvar') && ($this->ConfigProduto_model->add('categorias', $data) == true)) {
                $this->session->set_flashdata('success', 'Cadastro realizado!');
            } elseif(($this->input->post('acao')=='update') && ($this->ConfigProduto_model->edit('categorias', $dataUpdate, 'idCategoria', $idCategoria) == true) ){
                $this->session->set_flashdata('success', 'Atualização realizada!');
            }else {
                $this->session->set_flashdata('error', 'Não foi possivel realizar cadastro.');
            
            }
        }elseif($this->input->post('idMenu') == 'menu4') {
           $idMedida = $this->input->post('idMedida');
            $data = [
                'descricaoMedida' => strtoupper($this->input->post('idMedida')),
                'sigla' => strtoupper($this->input->post('sigla_medida')),
                'multiplicador' => $this->input->post('multiplicador'),
                'status' => $this->input->post('status'),
                'bloqueio' => 0,
                'idMedidaSistema' => $this->input->post('padrao'),
                   
            ];
            $dataUpdate = [ 
                'descricaoMedida' => strtoupper($this->input->post('medidaDescricao')),
                'sigla' => strtoupper($this->input->post('sigla_medida')),
                'multiplicador' => $this->input->post('multiplicador'),
                'status' => $this->input->post('status'),
                'idMedidaSistema' => $this->input->post('padrao'),
            ];
           
            if (($this->input->post('acao')=='salvar') && ($this->ConfigProduto_model->add('medidas', $data) == true)) {
                $this->session->set_flashdata('success', 'Cadastro realizado!');
            } elseif(($this->input->post('acao')=='update') && ($this->ConfigProduto_model->edit('medidas', $dataUpdate, 'idMedida', $idMedida) == true) ){
                $this->session->set_flashdata('success', 'Atualização realizada!');
            }else {
                $this->session->set_flashdata('error', 'Não foi possivel realizar cadastro.');
            
            }
        }

        redirect(site_url('produtos/gerenciar/')); 
    }

    public function visualizar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar produtos.');
            redirect(base_url());
        }

        $this->data['result'] = $this->produtos_model->getUnidadeProdutoID($this->uri->segment(3));
        

        if ($this->data['result'] == null) {
            $this->session->set_flashdata('error', 'Produto não encontrado.');
            redirect(site_url('produtos/editar/') . $this->input->post('idProdutos'));
        }

        $this->data['view'] = 'produtos/visualizarProduto';
        return $this->layout();
    }

    public function excluir()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir produtos.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null) {
            $this->session->set_flashdata('error', 'Erro ao tentar excluir produto.');
            redirect(base_url() . 'index.php/produtos/gerenciar/');
        }

        $this->produtos_model->delete('produtos_os', 'produtos_id', $id);
        $this->produtos_model->delete('itens_de_vendas', 'produtos_id', $id);
        $this->produtos_model->delete('produtos', 'idProdutos', $id);

        log_info('Removeu um produto. ID: ' . $id);

        $this->session->set_flashdata('success', 'Produto excluido com sucesso!');
        redirect(site_url('produtos/gerenciar/'));
    }

    public function atualizar_estoque()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eProduto')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para atualizar estoque de produtos.');
            redirect(base_url());
        }

        $idProduto = $this->input->post('id');
        $novoEstoque = $this->input->post('estoque');
        $estoqueAtual = $this->input->post('estoqueAtual');

        $estoque = $estoqueAtual + $novoEstoque;

        $data = [
            'estoque' => $estoque,
        ];

        if ($this->produtos_model->edit('produtos', $data, 'idProdutos', $idProduto) == true) {
            $this->session->set_flashdata('success', 'Estoque de Produto atualizado com sucesso!');
            log_info('Atualizou estoque de um produto. ID: ' . $idProduto);
            redirect(site_url('produtos/visualizar/') . $idProduto);
        } else {
            $this->data['custom_error'] = '<div class="alert">Ocorreu um erro.</div>';
        }
    }
}
