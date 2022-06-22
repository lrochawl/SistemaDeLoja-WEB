<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class NotasFiscais extends MY_Controller
{

    /**
     * author: Ramon Silva
     * email: silva018-mg@yahoo.com.br
     *
     */

    public function __construct()
    {
        parent::__construct();

        $this->load->model('notasfiscais_model');
        $this->data['menuNotasFiscais'] = 'Notas Fiscais';
    }

    public function index()
    {
        $this->gerenciar();
    }
    public function gerenciar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vArquivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar arquivos.');
            redirect(base_url());
        }

        $this->load->library('pagination');

        $pesquisa = $this->input->get('pesquisa');
        $de = $this->input->get('data');
        $ate = $this->input->get('data2');

        if ($pesquisa == null && $de == null && $ate == null) {
            $this->data['configuration']['base_url'] = site_url('notasficais/gerenciar');
            $this->data['configuration']['total_rows'] = $this->notasfiscais_model->count('notas_fiscais');

            $this->pagination->initialize($this->data['configuration']);

            $this->data['results'] = $this->notasfiscais_model->get('notas_fiscais', '*', '', $this->data['configuration']['per_page'], $this->uri->segment(3));
        } else {
            if ($de != null) {
                $de = explode('/', $de);
                $de = $de[2] . '-' . $de[1] . '-' . $de[0];

                if ($ate != null) {
                    $ate = explode('/', $ate);
                    $ate = $ate[2] . '-' . $ate[1] . '-' . $ate[0];
                } else {
                    $ate = $de;
                }
            }

            $this->data['results'] = $this->notasfiscais_model->search($pesquisa, $de, $ate);
        }

        $this->data['view'] = 'notas_fiscais/notasfiscais';
        return $this->layout();
    }
    public function autoCompleteFornecedor()
    {
        if (isset($_GET['term'])) {
            $q = strtolower($_GET['term']);
            $this->notasfiscais_model->autoCompleteFornecedor($q);
        }
    }

    public function adicionar()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aArquivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar arquivos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = 'Preencha todos os campos';

        $this->form_validation->set_rules('notaFiscal', '', 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $infoNF = $this->do_upload();

            $file = $infoNF['file_name'];
            $path = $infoNF['full_path'];
            $url = base_url() . 'assets/notas_fiscais/' .$arquivo['file_name']. date('d-m-Y') . '/' . $file;
            $tamanho = $infoNF['file_size'];
            $tipo = $infoNF['file_ext'];

            $data = $this->input->post('data');

            if ($data == null) {
                $data = date('Y-m-d');
            } else {
                $data = explode('/', $data);
                $data = $data[2] . '-' . $data[1] . '-' . $data[0];
            }

            $data = [
                'notaFiscal' => $this->input->post('notaFiscal'),
                'fornecedorId' => $this->input->post('fornecedor_id'),
                'descricao' => $this->input->post('descricao'),
                'file' => $file,
                'path' => $path,
                'url' => $url,
                'cadastro' => $data,
                'tamanho' => $tamanho,
                'tipo' => $tipo,
            ];
            
            if ($this->notasfiscais_model->add('notas_fiscais', $data) == true) {
                $this->session->set_flashdata('success', 'Arquivo adicionado com sucesso!');

                log_info('Adicionou uma Nota Fiscal');

                redirect(site_url('NotasFiscais/adicionar/'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['view'] = 'notas_fiscais/adicionarNotaFiscal';
        return $this->layout();
    }

    public function editar()
    {
        if (!$this->uri->segment(3) || !is_numeric($this->uri->segment(3))) {
            $this->session->set_flashdata('error', 'Item não pode ser encontrado, parâmetro não foi passado corretamente.');
            redirect('mapos');
        }

        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'eArquivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para editar arquivos.');
            redirect(base_url());
        }

        $this->load->library('form_validation');
        $this->data['custom_error'] = '';

        $this->form_validation->set_rules('notaFiscal', '', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">' . validation_errors() . '</div>' : false);
        } else {
            $data = $this->input->post('data');
            if ($data == null) {
                $data = date('Y-m-d');
            } else {
                $data = explode('/', $data);
                $data = $data[2] . '-' . $data[1] . '-' . $data[0];
            }

            $data = [
                'notaFiscal' => $this->input->post('notaFiscal'),
                'descricao' => $this->input->post('descricao'),
                'cadastro' => $data,
            ];

            if ($this->notasfiscais_model->edit('notas_fiscais', $data, 'idNotaFiscal', $this->input->post('idNotaFiscal')) == true) {
                $this->session->set_flashdata('success', 'Alterações efetuadas com sucesso!');
                log_info('Alterou um Nota Fical, ID: ' . $this->input->post('idNotaFiscal'));
                redirect(site_url('NotasFicais/editar/') . $this->input->post('idNotaFiscal'));
            } else {
                $this->data['custom_error'] = '<div class="form_error"><p>Ocorreu um erro.</p></div>';
            }
        }

        $this->data['result'] = $this->notasfiscais_model->getById($this->uri->segment(3));
        $this->data['view'] = 'notas_fiscais/editarNotaFiscal';
        return $this->layout();
    }

    public function download($id = null)
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'vArquivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para visualizar arquivos.');
            redirect(base_url());
        }

        if ($id == null || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Erro! O arquivo não pode ser localizado.');
            redirect(base_url() . 'index.php/NotasFiscais/');
        }

        $file = $this->notasfiscais_model->getById($id);
        $this->load->library('zip');
        $path = $file->path;
        $this->zip->read_file($path);
        $this->zip->download('file' . date('d-m-Y-H.i.s') . '.zip');
    }

    public function excluir()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'dArquivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para excluir arquivos.');
            redirect(base_url());
        }

        $id = $this->input->post('id');
        if ($id == null || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Erro! O arquivo não pode ser localizado.');
            redirect(site_url('NotasFiscais'));
        }

        $file = $this->notasfiscais_model->getById($id);
        $this->db->where('idNotasFiscais', $id);
        if ($this->db->delete('notas_fiscais')) {
            $path = $file->path;
            unlink($path);
            $this->session->set_flashdata('success', 'Arquivo excluido com sucesso!');
            log_info('Removeu um nota fiscal. ID: ' . $id);
        } else {
            $this->session->set_flashdata('error', 'Ocorreu um erro ao tentar excluir o arquivo.');
        }
        redirect(site_url('NotasFiscais'));
    }

    public function do_upload()
    {
        if (!$this->permission->checkPermission($this->session->userdata('permissao'), 'aArquivo')) {
            $this->session->set_flashdata('error', 'Você não tem permissão para adicionar arquivos.');
            redirect(base_url());
        }

        $date = date('d-m-Y');

        $config['upload_path'] = './assets/notas_fiscais/' . $date;
        $config['allowed_types'] = 'txt|jpg|jpeg|gif|png|pdf|PDF|JPG|JPEG|GIF|PNG';
        $config['max_size'] = 0;
        $config['max_width'] = '3000';
        $config['max_height'] = '2000';
        $config['encrypt_name'] = true;

        if (!is_dir('./assets/notas_fiscais/' . $date)) {
            mkdir('./assets/notas_fiscais/' . $date, 0777, true);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            $error = ['error' => $this->upload->display_errors()];

            $this->session->set_flashdata('error', 'Erro ao fazer upload da nota fiscal, verifique se a extensão do arquivo é permitida.');
            redirect(site_url('NotasFiscais/adicionar'));
        } else {
            //$data = array('upload_data' => $this->upload->data());
            return $this->upload->data();
        }
    }
}

/* End of file arquivos.php */
/* Location: ./application/controllers/arquivos.php */

