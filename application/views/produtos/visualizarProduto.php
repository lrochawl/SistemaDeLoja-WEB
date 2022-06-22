<div class="accordion" id="collapse-group">
    <div class="accordion-group widget-box">
        <div class="accordion-heading">
            <div class="widget-title" style="margin: -20px 0 0">
                <a data-parent="#collapse-group" href="#collapseGOne" data-toggle="collapse">
                    <span class="icon"><i class="fas fa-shopping-bag"></i></span>
                    <h5>Dados do Produto</h5>
                </a>
            </div>
        </div>
        <div class="collapse in accordion-body">
            <div class="widget-content">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td style="text-align: right; width: 30%"><strong>Código de Barra</strong></td>
                            <td>
                                <?php echo $result->codDeBarra   ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right; width: 30%"><strong>Descrição</strong></td>
                            <td>
                                <?php echo $result->descricao ?>
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align: right"><strong>Categoria</strong></td>
                            <td>
                                <?php echo $result->categoria ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Marca</strong></td>
                            <td>
                                <?php echo $result->marca ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Caracteristicas</strong></td>
                            <td>
                                <?php echo $result->caracteristicas ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Complemento</strong></td>
                            <td>
                                <?php echo $result->descricaoProduto ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Unidade</strong></td>
                            <td>
                                <?php echo $result->descricaoMedida ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Preço de Compra</strong></td>
                            <td>R$
                                <?php echo $result->precoCompra; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Margem de Lucro</strong></td>
                            <td>
                                <?php echo $result->margemLucro.'%' ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Preço de Venda</strong></td>
                            <td>R$
                                <?php echo $result->precoVenda; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Estoque</strong></td>
                            <td>
                                <?php 
                                 $fracionador = $result->multiplicador;
                                 $medidaDB = $result->estoque ;
                                 $resto = explode('.',$medidaDB );
                                 $medida = intval($resto['0']);
                                 $resto = intval($resto['1']);
                                
                                 if($result->sigla != $result->siglaMedidaSistema){
                                 if($resto > 0 && $medida > 0 ){
                                    $medida = $medida.' '.$result->sigla.' e '.$resto.$result->siglaMedidaSistema; 
                                 }elseif($resto > 0 && $medida < 0 ){
                                     $medida = ($resto * $fracionador).' '.$result->siglaMedidaSistema; 
                                  }elseif($resto == 0 && $medida > 0 ){
                                     $medida = $medida.' '.$result->sigla; 
                                  }
                                }else{
                                    if($resto > 0 && $medida > 0 ){
                                        $medida = $medida.' '.$result->sigla.' e '.$resto.$result->siglaFracaoSistema; 
                                     }elseif($resto > 0 && $medida < 0 ){
                                         $medida = ($resto * $fracionador).' '.$result->siglaFracaoSistema; 
                                      }elseif($resto == 0 && $medida > 0 ){
                                         $medida = $medida.' '.$result->sigla; 
                                      }
                                }
                                  echo $medida;                          
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Estoque Mínimo</strong></td>
                            <td>
                                <?php echo $result->estoqueMinimo.' '. $result->sigla; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Data de Compra</strong></td>
                            <td>
                                <?php echo $result->dataCompra ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Data de Vencimento</strong></td>
                            <td>
                                <?php echo $result->dataVencimento ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Data de Cadastro</strong></td>
                            <td>
                                <?php echo $result->dataCadastro ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><strong>Data de Atualização</strong></td>
                            <td>
                                <?php 
                                if($result->dataUpdate == ''){
                                  echo 'AINDA NÃO ATUALIZADO';  
                                }else{
                                echo $result->dataUpdate;
                                }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
