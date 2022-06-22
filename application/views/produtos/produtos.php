<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script src="<?php echo base_url() ?>assets/js/sweetalert2.all.min.js"></script>

<style>
    .flexxn {
        display: flex;
    }

    .modal.fade.in {
        top: -17%;
    }

    .help-block,
    .help-inline {
        padding-top: 3px;
        display: block !important;
        color: #999999;
    }

    @media (min-width: 1500px) {
        .row-fluid .offset3:first-child {
            margin-left: 51.641026%;
        }

        .modal {
            width: 30%;
        }

        .form-horizontal .control-group {
            border-top: 1px solid #ffffff;
            border-bottom: 1px solid #eeeeee;
            margin-bottom: 0;
            padding-right: 10%;
        }
    }

    @media (max-width: 480px) {
        .control-group {
            padding-left: 15%;
        }

        .flexxn {
            display: inline-grid;
        }
    }
</style>

<div class="new122" style="margin-top: 0; min-height: 100vh">
    <div class="flexxn">
        <?php if ($this->permission->checkPermission($this->session->userdata('permissao'), 'aProduto')) { ?>
            <a href="<?php echo base_url(); ?>index.php/produtos/adicionar" class="button btn btn-mini btn-success" style="max-width: 160px">
                <span class="button__icon"><i class='bx bx-plus-circle'></i></span><span class="button__text2">Add. Produtos</span></a>
            <a href="#modal-etiquetas" role="button" data-toggle="modal" class="button btn btn-mini btn-warning" style="max-width: 160px">
                <span class="button__icon"><i class='bx bx-barcode-reader'></i></span><span class="button__text2">Gerar Etiquetas</span></a>
            <a href="#modal-config" role="button" data-toggle="modal" class="button btn btn-mini btn-primary" style="max-width: 160px">
                <span class="button__icon"><i class='bx  bx-abacus'></i></span><span class="button__text2">Configurar</span></a>
    </div>

<?php } ?>

<div class="widget-box">
    <div class="widget-title" style="margin: -20px 0 0">
        <span class="icon">
            <i class="fas fa-shopping-bag"></i>
        </span>
        <h5>Produtos</h5>
    </div>
    <div class="widget-content nopadding tab-content">
        <table id="tabela" class="table table-bordered ">
            <thead>
                <tr>
                    <th>Cod.</th>
                    <th>Cod. Barra</th>
                    <th>Produto</th>
                    <th>Estoque</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php

                if (!$results) {
                    echo '<tr>
                                <td colspan="6">Nenhum Produto Cadastrado</td>
                                </tr>';
                }

                foreach ($results as $r) {

                    $fracionador = $r->multiplicador;
                    $medidaDB = $r->estoque;
                    $resto = explode('.', $medidaDB);
                    $medida = intval($resto['0']);
                    $resto = intval($resto['1']);

                    if ($r->sigla != $r->siglaMedidaSistema) {
                        if ($resto > 0 && $medida > 0) {
                            $medida = $medida . ' ' . $r->sigla . '+';
                        } elseif ($resto > 0 && $medida < 0) {
                            $medida = ($resto * $fracionador) . ' ' . $r->siglaMedidaSistema;
                        } elseif ($resto == 0 && $medida > 0) {
                            $medida = $medida . ' ' . $r->sigla;
                        }
                    } else {
                        if ($resto > 0 && $medida > 0) {
                            $medida = $medida . ' ' . $r->sigla . '+';
                        } elseif ($resto > 0 && $medida < 0) {
                            $medida = ($resto * $fracionador) . ' ' . $r->siglaFracaoSistema;
                        } elseif ($resto == 0 && $medida > 0) {
                            $medida = $medida . ' ' . $r->sigla;
                        }
                    }



                    echo '<tr>';
                    echo '<td>' . $r->idProdutos . '</td>';
                    echo '<td>' . $r->codDeBarra . '</td>';
                    echo '<td>' . $r->descricao . '</td>';
                    echo  '<td>' . $medida . '</td>';
                    echo '<td> R$' . number_format($r->precoVenda, 2, ',', '.') . '</td>';
                    echo '<td>';
                    if ($this->permission->checkPermission($this->session->userdata('permissao'), 'vProduto')) {
                        echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/produtos/visualizar/' . $r->idProdutos . '" class="btn-nwe" title="Visualizar Produto"><i class="bx bx-show bx-xs"></i></a>  ';
                    }
                    if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eProduto')) {
                        echo '<a style="margin-right: 1%" href="' . base_url() . 'index.php/produtos/editar/' . $r->idProdutos . '" class="btn-nwe3" title="Editar Produto"><i class="bx bx-edit bx-xs"></i></a>';
                    }
                    if ($this->permission->checkPermission($this->session->userdata('permissao'), 'dProduto')) {
                        echo '<a style="margin-right: 1%" href="#modal-excluir" role="button" data-toggle="modal" produto="' . $r->idProdutos . '" class="btn-nwe4" title="Excluir Produto"><i class="bx bx-trash-alt bx-xs"></i></a>';
                    }
                    if ($this->permission->checkPermission($this->session->userdata('permissao'), 'eProduto')) {
                        echo '<a href="#atualizar-estoque" role="button" data-toggle="modal" produto="' . $r->idProdutos . '" estoque="' . $r->estoque . '" class="btn-nwe5" title="Atualizar Estoque"><i class="bx bx-plus-circle bx-xs"></i></a>';
                    }
                    echo '</td>';
                    echo '</tr>';
                } ?>
            </tbody>
        </table>
    </div>
</div>
<?php echo $this->pagination->create_links(); ?>

<!-- Modal -->
<div id="modal-excluir" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/produtos/excluir" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel"><i class="fas fa-trash-alt"></i> Excluir Produto</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" id="idProduto" class="idProduto" name="id" value="" />
            <h5 style="text-align: center">Deseja realmente excluir este produto?</h5>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true">
                <span class="button__icon"><i class="bx bx-x"></i></span><span class="button__text2">Cancelar</span></button>
            <button class="button btn btn-danger" id="bt-salvar"><span class="button__icon"><i class='bx bx-trash'></i></span> <span class="button__text2">Excluir</span></button>
        </div>
    </form>
</div>

<!-- Modal Estoque -->
<div id="atualizar-estoque" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/produtos/atualizar_estoque" method="post" id="formEstoque">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel"><i class="fas fa-plus-square"></i> Atualizar Estoque</h5>
        </div>
        <div class="modal-body">
            <div class="control-group">
                <label for="estoqueAtual" class="control-label">Estoque Atual</label>
                <div class="controls">
                    <input id="estoqueAtual" type="text" name="estoqueAtual" value="" readonly />
                </div>
            </div>

            <div class="control-group">
                <label for="estoque" class="control-label">Adicionar Produtos<span class="required">*</span></label>
                <div class="controls">
                    <input type="hidden" id="idProduto" class="idProduto" name="id" value="" />
                    <input id="estoque" type="text" name="estoque" value="" />
                </div>
            </div>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="button btn btn-primary"><span class="button__icon"><i class="bx bx-sync"></i></span><span class="button__text2">Atualizar</span></button>
            <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i class="bx bx-x"></i></span><span class="button__text2">Cancelar</span></button>
        </div>
    </form>
</div>

<!-- Modal Etiquetas -->
<div id="modal-etiquetas" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form action="<?php echo base_url() ?>index.php/relatorios/produtosEtiquetas" method="get">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h5 id="myModalLabel">Gerar etiquetas com Código de Barras</h5>
        </div>
        <div class="modal-body">
            <div class="span12 alert alert-info" style="margin-left: 0"> Escolha o intervalo de produtos para gerar as etiquetas.</div>

            <div class="span12" style="margin-left: 0;">
                <div class="span6" style="margin-left: 0;">
                    <label for="valor">De</label>
                    <input class="span9" style="margin-left: 0" type="text" id="de_id" name="de_id" placeholder="ID do primeiro produto" value="" />
                </div>


                <div class="span6">
                    <label for="valor">Até</label>
                    <input class="span9" type="text" id="ate_id" name="ate_id" placeholder="ID do último produto" value="" />
                </div>

                <div class="span4">
                    <label for="valor">Qtd. do Estoque</label>
                    <input class="span12" type="checkbox" name="qtdEtiqueta" value="true" />
                </div>

                <div class="span6">
                    <label class="span12" for="valor">Formato Etiqueta</label>
                    <select class="span5" name="etiquetaCode">
                        <option value="EAN13">EAN-13</option>
                        <option value="UPCA">UPCA</option>
                        <option value="C93">CODE 93</option>
                        <option value="C128A">CODE 128</option>
                        <option value="CODABAR">CODABAR</option>
                        <option value="QR">QR-CODE</option>
                    </select>
                </div>

            </div>
        </div>
        <div class="modal-footer" style="display:flex;justify-content: center">
            <button class="button btn btn-warning" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i class="bx bx-x"></i></span><span class="button__text2">Cancelar</span></button>
            <button class="button btn btn-success"><span class="button__icon"><i class='bx bx-barcode'></i></span><span class="button__text2">Gerar</span></button>
        </div>
    </form>
</div>

<!-- Modal Configurações cadastro de produtos -->
<div id="modal-config" class="modal  hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="row-fluid" style="margin-top:0">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title" style="margin: -20px 0 0">
                    <span class="icon">
                        <i class="fas fa-wrench"></i>
                    </span>
                    <h5>Configurações para cadastro de produto</h5>
                </div>
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#menu1">Marca</a></li>
                    <li><a data-toggle="tab" href="#menu2">Produto</a></li>
                    <li><a data-toggle="tab" href="#menu3">Categoria</a></li>
                    <li><a data-toggle="tab" href="#menu4">Medida</a></li>

                </ul>

                <div class="widget-content nopadding tab-content">
                    <?php echo $custom_error; ?>


                    <!-- Menu Marca -->
                    <div id="menu1" class="tab-pane fade  in active">
                        <form action="<?php echo base_url() ?>index.php/produtos/config_produto" id="nova_marca" method="post" class="form-horizontal">
                            <div class="alert alert-primary" role="alert" style="display: none;" id="msg-alert1">
                                Dados salvos com sucesso!
                            </div>
                            <input name="idMenu" type="hidden" value="menu1" />
                            <input name="acao" type="hidden" value="salvar" id="acao_1" />
                            <input id="pesquisa_marca_id" type="hidden" name="idMarca" value="" />
                            <div class="control-group">
                                <label for="control_baixa" class="control-label">Marca</label>
                                <div class="controls">
                                    <input name="pesquisa_marca" type="text" value="" id="pesquisa_marca" />
                                    <span class="help-inline">Cadastrar nova marca.</span>
                                </div>
                            </div>

                            <div class="control-group" id="atualiza-marca" style="display: none;">
                                <label for="control_editos" class="control-label">Atualizar nome</label>
                                <div class="controls">
                                    <input name="marca" type="text" value="" id="nome_marca" />
                                    <span class="help-inline">Atualiza marca cadastrada.</span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="control_editos" class="control-label">Sigla</label>
                                <div class="controls">
                                    <input name="sigla" type="text" value="" id="sigla_marca" />
                                    <span class="help-inline">Vincular sigla a marca nova a ser cadastrada.</span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="seleciona_situacao" class="control-label">Status</label>
                                <div class="controls">
                                    <select name="status" id="seleciona_status_marca">
                                        <option value="1">Ativo</option>
                                        <option value="0">Inativo</option>
                                    </select>
                                    <span class="help-inline">Selecionar marca para adcionar um produto.</span>
                                </div>
                            </div>

                            <div class="form-actions">
                                <div class="span8">
                                    <div class="span9 offset3" style="display: flex;justify-content: center">
                                        <button type="submit" class="button btn btn-danger" id="bt-salvar1">
                                            <span class="button__icon"><i id="bt_save-icon1" class="bx bx-save"></i></span>
                                            <span class="button__text2" id="bt_save-text1">Salvar</span>
                                        </button>
                                        <button class="button btn btn-mini btn-warning" data-dismiss="modal" aria-hidden="true">
                                            <span class="button__icon"><i class="bx bx-x"></i></span>
                                            <span class="button__text2">Cancelar</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <!-- Menu Produto -->
                    <div id="menu2" class="tab-pane fade">
                        <form action="<?php echo base_url() ?>index.php/produtos/config_produto" id="novo_produto" method="post" class="form-horizontal">
                            <div class="alert alert-primary" role="alert" style="display: none;" id="msg-alert2">
                                Dados salvos com sucesso!
                            </div>
                            <input name="idMenu" type="hidden" value="menu2" />
                            <input name="acao" type="hidden" value="salvar" id="acao_2" />
                            <input id="pesquisa_tipo_produto_id" type="hidden" name="idProduto" value="" />

                            <div class="control-group">
                                <label for="novo_produto" class="control-label">Pesquisar produto por descição</label>
                                <div class="controls">
                                    <input name="produtoPesquisa" type="text" value="" id="pesquisa_tipo_produto" />
                                    <span class="help-inline">Cadastrar novo produto a marca.</span>
                                </div>
                            </div>
                            <div class="control-group" id="atualiza-produto" style="display: none;">
                                <label for="novo_produto" class="control-label">Alterar nome do produto</label>
                                <div class="controls">
                                    <input name="produto" type="text" value="" id="altera_tipo_produto" />
                                    <span class="help-inline">Cadastrar novo produto a marca.</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="seleciona_marca" class="control-label">Marca</label>
                                <div class="controls">
                                    <select name="idMarcaProduto" id="seleciona_marca">
                                        <option disabled selected>Selecione um produto</option>
                                        <?php foreach ($m as $marca) {
                                            echo  "<option value='$marca->idMarca' >$marca->marca</option>";
                                        } ?>
                                    </select>
                                    <span class="help-inline">Selecionar marca para adcionar um produto.</span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="seleciona_status_produto" class="control-label">Status</label>
                                <div class="controls">
                                    <select name="status" id="seleciona_status_produto">
                                        <option value="1">Ativo</option>
                                        <option value="0">Inativo</option>
                                    </select>
                                    <span class="help-inline">Selecionar marca para adcionar um produto.</span>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="span8">
                                    <div class="span9 offset3" style="display: flex;justify-content: center">
                                        <button type="submit" class="button btn btn-danger" id="bt-salvar2">
                                            <span class="button__icon"><i id="bt_save-icon2" class="bx bx-save"></i></span>
                                            <span class="button__text2" id="bt_save-text2">Salvar</span>
                                        </button>
                                        <button class="button btn btn-mini btn-warning" data-dismiss="modal" aria-hidden="true">
                                            <span class="button__icon"><i class="bx bx-x"></i></span>
                                            <span class="button__text2">Cancelar</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Menu Categoria -->
                    <div id="menu3" class="tab-pane fade">
                        <form action="<?php echo base_url() ?>index.php/produtos/config_produto" id="nova_categoria" method="post" class="form-horizontal">
                            <div class="alert alert-primary" role="alert" style="display: none;" id="msg-alert3">
                                Dados salvos com sucesso!
                            </div>
                            <input name="idMenu" type="hidden" value="menu3" />
                            <input name="acao" type="hidden" value="salvar" id="acao_3" />
                            <input id="pesquisa_categoria_id" type="hidden" name="idCategoria" value="" />
                            <div class="control-group">
                                <label for="control_baixa" class="control-label">Categoria</label>
                                <div class="controls">
                                    <input name="pesquisa_categoria" type="text" value="" id="pesquisa_categoria" />
                                    <span class="help-inline">Cadastrar nova categoria.</span>
                                </div>
                            </div>

                            <div class="control-group" id="atualiza-categoria" style="display: none;">
                                <label for="control_editos" class="control-label">Atualizar nome</label>
                                <div class="controls">
                                    <input name="categoria" type="text" value="" id="nome_categoria" />
                                    <span class="help-inline">Atualiza categoria cadastrada.</span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="control_editos" class="control-label">Sigla</label>
                                <div class="controls">
                                    <input name="siglaCategoria" type="text" value="" id="sigla_categoria" />
                                    <span class="help-inline">Vincular sigla a categoria cadastrada.</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="seleciona_status" class="control-label">Status</label>
                                <div class="controls">
                                    <select name="statusCategoria" id="seleciona_status_categoria">
                                        <option value="1">Ativo</option>
                                        <option value="0">Inativo</option>
                                    </select>
                                    <span class="help-inline">Selecionar o status da categoria.</span>
                                </div>
                            </div>

                            <div class="form-actions">
                                <div class="span8">
                                    <div class="span9 offset3" style="display: flex;justify-content: center">
                                        <button type="submit" class="button btn btn-danger" id="bt-salvar3">
                                            <span class="button__icon"><i id="bt_save-icon3" class="bx bx-save"></i></span>
                                            <span class="button__text2" id="bt_save-text3">Salvar</span>
                                        </button>
                                        <button class="button btn btn-mini btn-warning" data-dismiss="modal" aria-hidden="true">
                                            <span class="button__icon"><i class="bx bx-x"></i></span>
                                            <span class="button__text2">Cancelar</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <!-- Menu Medida -->
                    <div id="menu4" class="tab-pane fade">
                        <form action="<?php echo base_url() ?>index.php/produtos/config_produto" id="editar_madida" method="post" class="form-horizontal">
                            <div class="alert alert-primary" role="alert" style="display: none;" id="msg-alert4">
                                Dados salvos com sucesso!
                            </div>
                            <input name="idMenu" type="hidden" value="menu4" />
                            <input name="acao" type="hidden" value="salvar" id="acao_4" />
                            <input name="idMedida" type="hidden" value="" id="pesquisa_medida_id" />
                            <div class="control-group">
                                <label for="editar_medida" class="control-label">Pesquisar Medida</label>
                                <div class="controls">
                                    <input name="produto" type="text" value="" id="pesquisa_medida" />
                                    <span class="help-inline" id="msg-medida">Cadastrar ou auterar produto.</span>
                                    <!-- <select name="idMedida" id="medida">
                                    <option value='' >Selecione uma medida</option>;
                                        <?php /*foreach ($medidas as $medida) {
                                            echo  "<option value='$medida->idMedida' >$medida->descricao</option>";
                                        } */ ?>
                                    </select> -->
                                </div>
                            </div>
                            <div class="control-group" id="atualiza_medida" style="display: none;">
                                <label for="medida" class="control-label">Nome da unidade de medida</label>
                                <div class="controls">
                                    <input name="medidaDescricao" type="text" value="" id="descricao_medida" />
                                    <span class="help-inline">Altere este campo para realizar modificação.</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="sigla_medida" class="control-label">Sigla</label>
                                <div class="controls">
                                    <input name="sigla_medida" type="text" value="" id="sigla_medida" />
                                    <span class="help-inline">Altere este campo para realizar modificação.</span>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label for="multiplicador" class="control-label">Fator de Multiplicação</label>
                                <div class="controls">
                                    <input id="multiplicador" type="number" value="" id="multiplicador" name="multiplicador" />
                                    <span class="help-inline">Altere este campo para realizar modificação.</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="control_editos" class="control-label">Padrão</label>
                                <div class="controls">
                                    <select name="padrao" id="padrao">
                                        <option disabled selected>Selecionar</option>
                                        <?php if (!$medidasSistema) {
                                            echo '<option value="">Sem categorias cadastradas</option>';
                                        }

                                        foreach ($medidasSistema as $r) {
                                            if($results){}
                                            echo "<option value=$r->idMedidaSistema >$r->nomeMedidaSistema</option>";
                                        }
                                        ?>
                                    </select>
                                    <span class="help-inline">Selecione unidade de medida padrão.</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="seleciona_status_medida" class="control-label">Status</label>
                                <div class="controls">
                                    <select name="status" id="seleciona_status_medida">
                                        <option value="1">Ativo</option>
                                        <option value="0">Inativo</option>
                                    </select>
                                    <span class="help-inline">O status inativo vai ocultar o produto no sistema.</span>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="span8">
                                    <div class="span9 offset3" style="display: flex;justify-content: center">
                                        <button type="submit" class="button btn btn-danger" id="bt-salvar4">
                                            <span class="button__icon"><i id="bt_save-icon4" class="bx bx-save"></i></span>
                                            <span class="button__text2" id="bt_save-text4">Salvar</span>
                                        </button>
                                        <button class="button btn btn-mini btn-warning" data-dismiss="modal" aria-hidden="true">
                                            <span class="button__icon"><i class="bx bx-x"></i></span>
                                            <span class="button__text2">Cancelar</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal -->
        <div id="modal-confirmaratualiza" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form action="<?php echo base_url() ?>index.php/clientes/excluir" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h5 id="myModalLabel">Atualização de sistema</h5>
                </div>
                <div class="modal-body">
                    <h5 style="text-align: left">Deseja realmente fazer a atualização de sistema?</h5>
                    <h7 style="text-align: left">Recomendamos que faça um backup antes de prosseguir!</h7>
                    <h7 style="text-align: left"><br>Faça o backup dos seguintes arquivos pois os mesmo serão excluídos:</h7>
                    <h7 style="text-align: left"><br>* ./assets/anexos</h7>
                    <h7 style="text-align: left"><br>* ./assets/arquivos</h7>
                </div>
                <div class="modal-footer" style="display:flex;justify-content: center">
                    <button class="button btn btn-mini btn-danger" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i class='bx bx-x'></i></span> <span class="button__text2">Cancelar</span></button>
                    <button id="update-mapos" type="button" class="button btn btn-warning"><span class="button__icon"><i class="bx bx-sync"></i></span><span class="button__text2">Atualizar</span></button>
                </div>
            </form>
        </div>
        <!-- Modal -->
        <div id="modal-confirmabanco" class="modal hide fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <form action="<?php echo base_url() ?>index.php/clientes/excluir" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h5 id="myModalLabel">Atualização de sistema</h5>
                </div>
                <div class="modal-body">
                    <h5 style="text-align: left">Deseja realmente fazer a atualização do banco de dados?</h5>
                    <h7 style="text-align: left">Recomendamos que faça um backup antes de prosseguir!
                        <a target="_blank" title="Fazer Bakup" class="btn btn-mini btn-inverse" href="<?php echo site_url() ?>/mapos/backup">Fazer Backup</a>
                    </h7>
                </div>
                <div class="modal-footer" style="display:flex;justify-content: center">
                    <button class="button btn btn-mini btn-danger" data-dismiss="modal" aria-hidden="true"><span class="button__icon"><i class='bx bx-x'></i></span> <span class="button__text2">Cancelar</span></button>
                    <button id="update-database" type="button" class="button btn btn-warning"><span class="button__icon"><i class="bx bx-sync"></i></span><span class="button__text2">Atualizar</span></button>
                </div>
            </form>
        </div>
        <script>
            $('#update-database').click(function() {
                window.location = "<?= site_url('mapos/atualizarBanco') ?>"
            });
            $('#update-mapos').click(function() {
                window.location = "<?= site_url('mapos/atualizarMapos') ?>"
            });
            $(document).ready(function() {
                $('#notifica_whats_select').change(function() {
                    if ($(this).val() != "0")
                        document.getElementById("notifica_whats").value += $(this).val();
                    $(this).prop('selectedIndex', 0);
                });
            });
        </script>

    </div>

</div>

<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<!-- Modal Etiquetas e Estoque-->
<script type="text/javascript">
    $(document).ready(function() {
        $("#seleciona_marca").select2({
    dropdownParent: $("#modal-config")
  });

        $(document).on('click', 'a', function(event) {
            var produto = $(this).attr('produto');
            var estoque = $(this).attr('estoque');
            $('.idProduto').val(produto);
            $('#estoqueAtual').val(estoque);
        });

        $('#formEstoque').validate({
            rules: {
                estoque: {
                    required: true,
                    number: true
                }
            },
            messages: {
                estoque: {
                    required: 'Campo Requerido.',
                    number: 'Informe um número válido.'
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.control-group').removeClass('error');
                $(element).parents('.control-group').addClass('success');
            }
        });


        //auto complete 
        $('button#bt-salvar1').attr("disabled", true);
        $('button#bt-salvar2').attr("disabled", true);
        $('button#bt-salvar3').attr("disabled", true);
        $("#pesquisa_marca").autocomplete({
            source: "<?php echo base_url(); ?>index.php/produtos/autoCompleteMarca",
            minLength: 1,
            close: function(ui) {
                if (ui.label == 'Adicionar marca...')
                    ui.target.value = '';
            },
            select: function(event, ui) {
                $('button#bt-salvar1').attr("disabled", true);
                if (ui.item.id == null) {
                    $("#idMarcaProduto").val(ui.item.idMarcaProduto);
                    $("#pesquisa_marca_id").val(ui.item.marca);
                    $("#nome_marca").val('');
                    $("#sigla_marca").val('');
                    $("#seleciona_status_marca").val(1).change();
                    $('#acao_1').val('salvar');
                    $('#atualiza-marca').hide();
                    $('#bt_save-icon1').attr('class', 'bx bx-save');
                    $('#bt_save-text1').text('Salvar');
                    $('button#bt-salvar1').attr("disabled", false);
                } else {
                    $("#idMarcaProduto").val(ui.item.idMarcaProduto);
                    $("#pesquisa_marca_id").val(ui.item.id);
                    $("#nome_marca").val(ui.item.marca);
                    $("#sigla_marca").val(ui.item.siglaMarca);
                    $("#seleciona_status_marca").val(ui.item.statusMarca).change();
                    $('#acao_1').val('update');
                    $('#atualiza-marca').show();
                    $('#bt_save-icon1').attr('class', 'bx bx-sync');
                    $('#bt_save-text1').text('Atualizar');
                    $('button#bt-salvar1').attr("disabled", false);
                }
            }
        });
        $("#pesquisa_tipo_produto").autocomplete({
            source: "<?php echo base_url(); ?>index.php/produtos/autoCompleteTipoProduto",
            minLength: 1,
            close: function(ui) {
                if (ui.label == 'Adicionar marca...')
                    ui.target.value = '';
            },
            select: function(event, ui) {
                $('button#bt-salvar2').attr("disabled", true);
                if (ui.item.id == null) {
                    $("#pesquisa_tipo_produto_id").val(ui.item.produto);
                    $("#pesquisa_tipo_produto").val('');
                    $("#altera_tipo_produto").val('');
                    $("#seleciona_marca").val(0).change();
                    $("#seleciona_status_produto").val(1).change();
                    $('#acao_2').val('salvar');
                    $('#atualiza-produto').hide();
                    $('#bt_save-icon2').attr('class', 'bx bx-save');
                    $('#bt_save-text2').text('Salvar');
                    $('button#bt-salvar2').attr("disabled", false);
                } else {
                    $("#pesquisa_tipo_produto_id").val(ui.item.id);
                    $("#pesquisa_tipo_produto").val(ui.item.tipoProduto);
                    $("#altera_tipo_produto").val(ui.item.tipoProduto);
                    $("#seleciona_marca").val(ui.item.idMarcaProduto).change();
                    $("#seleciona_status_produto").val(ui.item.statusProduto).change();
                    $('#acao_2').val('update');
                    $('#atualiza-produto').show();
                    $('#bt_save-icon2').attr('class', 'bx bx-sync');
                    $('#bt_save-text2').text('Atualizar');
                    $('button#bt-salvar2').attr("disabled", false);
                }
            }
        });
        $("#pesquisa_categoria").autocomplete({
            source: "<?php echo base_url(); ?>index.php/produtos/autoCompleteCategoria",
            minLength: 1,
            close: function(ui) {
                if (ui.label == 'Adicionar categoria...')
                    ui.target.value = '';
            },
            select: function(event, ui) {
                $('button#bt-salvar3').attr("disabled", true);

                if (ui.item.id == null) {
                    $("#pesquisa_categoria_id").val(ui.item.categoria);
                    $("#pesquisa_categoria").val('');
                    $("#nome_categoria").val('');
                    $("#sigla_categoria").val('');
                    $("#seleciona_status_categoria").val(1).change();
                    $('#acao_3').val('salvar');
                    $('#atualiza-produto').hide();
                    $('#bt_save-icon3').attr('class', 'bx bx-save');
                    $('#bt_save-text3').text('Salvar');
                    $('button#bt-salvar3').attr("disabled", false);
                } else {
                    $("#pesquisa_categoria_id").val(ui.item.id);
                    $("#pesquisa_categoria").val(ui.item.descricaoCategoria);
                    $("#nome_categoria").val(ui.item.descricaoCategoria);
                    $("#sigla_categoria").val(ui.item.siglaCategoria);;
                    $("#seleciona_status_categoria").val(ui.item.statusCategoria).change();
                    $('#acao_3').val('update');
                    $('#atualiza-produto').show();
                    $('#bt_save-icon3').attr('class', 'bx bx-sync');
                    $('#bt_save-text3').text('Atualizar');
                    $('button#bt-salvar3').attr("disabled", false);
                }
            }
        });
        $("#pesquisa_medida").autocomplete({
            source: "<?php echo base_url(); ?>index.php/produtos/autoCompleteMedida",
            minLength: 1,
            close: function(ui) {
                if (ui.label == 'Adicionar marca...')
                    ui.target.value = '';
            },
            select: function(event, ui) {
                $('button#bt-salvar4').attr("disabled", true);
                if (ui.item.id == null) {
                    $("#pesquisa_medida_id").val(ui.item.medida);
                    $("#descricao_medida").val('');
                    $("#descricao_medida").prop("readonly", false);
                    $("#casas_decimais").val('');
                    $("#casas_decimais").prop("readonly", false);
                    $("#multiplicador").val('');
                    $("#multiplicador").prop("readonly", false);
                    $("#sigla_medida").val('');
                    $("#sigla_medida").prop("readonly", false);
                    $("#seleciona_status_medida").val(1).change();
                    $("#seleciona_status_medida").attr("readonly", false);
                    $('button#bt-salvar4').attr("disabled", false);
                    $('#atualiza_medida').hide();
                    $('#bt_save-icon4').attr('class', 'bx bx-save');
                    $('#bt_save-text4').text('Salvar');
                    $('#acao_4').val('salvar');
                    $('#padrao').attr("readonly", false);
                } else {
                    if (ui.item.bloqueio == 0) {
                        $("#pesquisa_medida_id").val(ui.item.id);
                        $("#descricao_medida").val(ui.item.descricaoMedida);
                        $("#descricao_medida").prop("readonly", false);
                        $("#casas_decimais").val(ui.item.casasDecimais);
                        $("#casas_decimais").prop("readonly", false);
                        $("#multiplicador").val(ui.item.multiplicador);
                        $("#multiplicador").prop("readonly", false);
                        $("#sigla_medida").val(ui.item.siglaMedida);
                        $("#sigla_medida").prop("readonly", false);
                        $("#seleciona_status_medida").val(ui.item.statusMedida).change();
                        $("#seleciona_status_medida").attr("readonly", false);
                        $('button#bt-salvar4').attr("disabled", false);
                        $('#atualiza_medida').show();
                        $('#bt_save-icon4').attr('class', 'bx bx-sync');
                        $('#bt_save-text4').text('Atualizar');
                        $('#acao_4').val('update');
                        $('#padrao').val(ui.item.idMedidaSistema).change();
                    } else {
                        $("#pesquisa_medida_id").val(ui.item.id);
                        $("#descricao_medida").val(ui.item.descricaoMedida);
                        $("#descricao_medida").prop("readonly", true);
                        $("#casas_decimais").val(ui.item.casasDecimais);
                        $("#casas_decimais").prop("readonly", true);
                        $("#multiplicador").val(ui.item.multiplicador);
                        $("#multiplicador").prop("readonly", true);
                        $("#sigla_medida").val(ui.item.siglaMedida);
                        $("#sigla_medida").prop("readonly", true);
                        $("#seleciona_status_medida").val(ui.item.statusMedida).change();
                        $("#seleciona_status_medida").attr('readonly', 'readonly');
                        $('button#bt-salvar4').attr("disabled", true);
                        $('#atualiza_medida').show();
                        $('#bt_save-icon4').attr('class', 'bx bx-sync');
                        $('#bt_save-text4').text('Atualizar');
                        $('#msg-medida').text('Medida padrão do sistema não pode ser alterada');
                        $('#msg-medida').css('color', 'red');
                        $('#acao_4').val('update');
                        $('#padrao').val(ui.item.idMedidaSistema).change();
                        $('#padrao').attr("disabled", true);
                    }

                }
            }
        });



    });
</script>