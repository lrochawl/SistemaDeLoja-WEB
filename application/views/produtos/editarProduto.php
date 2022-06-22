<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" />
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/auth/login.js"></script>
<style>
    /* Hiding the checkbox, but allowing it to be focused */
    
    .form_error{
        color:red;
        margin-left: 2%;
        font-size: 1.5em;
    }
    .badgebox {
        opacity: 0;
    }

    .badgebox+.badge {
        /* Move the check mark away when unchecked */
        text-indent: -999999px;
        /* Makes the badge's width stay the same checked and unchecked */
        width: 27px;
    }

    .badgebox:focus+.badge {
        /* Set something to make the badge looks focused */
        /* This really depends on the application, in my case it was: */

        /* Adding a light border */
        box-shadow: inset 0px 0px 5px;
        /* Taking the difference out of the padding */
    }

    .badgebox:checked+.badge {
        /* Move the check mark back when checked */
        text-indent: 0;
    }
</style>
<div class="row-fluid" style="margin-top:0">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title" style="margin: -20px 0 0">
                <span class="icon">
                    <i class="fas fa-shopping-bag"></i>
                </span>
                <h5>Editar Produto</h5>
            </div>
            <form action="<?php echo current_url(); ?>" id="formProduto" method="post" class="form-horizontal">
            
            <div class="widget-content nopadding tab-content" style="margin-bottom: 2%;">
           
                <div class="span6">
                    <?php echo $custom_error; ?>
                    <input id="produto_id" type="hidden" name="codDeBarra" value="<?php echo $result->codDeBarra; ?>" /> 
                <input id="dataUpdate" type="hidden" name="dataUpdate" value="<?php echo date('Y-m-d'); ?>" />
                <input id="adNotaFiscal_id" type="hidden" name="adNotaFiscal_id" value="<?php  echo $result->notaFiscal; ?>" />
                    <div class="control-group">
                        <?php echo form_hidden('idProdutos', $result->idProdutos) ?>
                        <label for="codDeBarra" class="control-label">Código de Barra<span class=""></span></label>
                        <div class="controls">
                            <input id="codDeBarra" type="text" name="codDeBarra" value="<?php echo $result->codDeBarra; ?>" readonly />
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="adNotaFiscal" class="control-label">Nota Fiscal<span class="required">*</span></label>
                        <div class="controls">
                            <input type="text" id="adNotaFiscal" name="adNotaFiscal" value="<?php  echo $result->notaFiscal; ?>" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="descricao" class="control-label">Descrição<span class="required">*</span></label>
                        <div class="controls">
                            <input type="text" id="descricao" name="descricao" value="<?php echo $result->descricao; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="categorias" class="control-label">Categoria<span class="required">*</span></label>
                        <div class="controls">
                            <select id="categorias" name="categoria" required>
                                
                                <?php if (!$resultCategoria) {
                                    echo '<option disabled selected>Sem categorias cadastradas</option>';
                                }

                                foreach ($resultCategoria as $r) {
                                    if($result->categoriaId == $r->idCategoria){
                                        echo  "<option value='$result->categoriaId' selected>$r->categoria</option>" ;
                                    }elseif($result->categoriaId != $r->idCategoria){
                                      echo "<option value='$r->idCategoria' >$r->categoria</option>";  
                                    }
                            
                                    
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="marca" class="control-label">Marca<span class="required">*</span></label>
                        <div class="controls">
                            <select id="marcasAgrotec" name="marca" required>

                                <?php if (!$resultMarca) {
                                    echo '<option disabled selected>Sem marcas cadastradas</option>';
                                }

                                foreach ($resultMarca as $r) {
                                    if($result->marcaId == $r->idMarca){
                                        echo  "<option value='$r->idMarca' selected>$r->marca</option>" ;
                                    }elseif($result->marcaId != $r->idMarca && $r->status != 0){
                                        echo "<option value='$r->idMarca' >$r->marca</option>";
                                    }
                                    
                                }
                                ?>
                            </select>


                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label for="marca" class="control-label">Complemento<span class="required">*</span></label>
                        <div class="controls">
                            <select name="complemento" id="tipoMarca">
                               <?= "<option value='$result->idTipo'>$result->descricaoProduto</option>" ?>
                           <!-- Adicionado via ajax -->
                            </select>

                        </div>
                    </div>

                    <div class="control-group">
                        <label for="caracteristicas" class="control-label">Caracteristicas<span class="required">*</span></label>
                        <div class="controls">
                            <input id="caracteristicas" type="text" name="caracteristicas" value="<?php echo $result->caracteristicas; ?>" />
                        </div>
                    </div>


                </div>
                <div class="span6">
                    <div class="control-group">
                        <label for="precoCompra" class="control-label">Preço de Compra (R$)<span class="required">*</span></label>
                        <div class="controls">
                            <input style="width: 5em;" id="precoCompra" class="money" data-affixes-stay="true" data-thousands="" data-decimal="." type="text" name="precoCompra" value="<?php echo $result->precoCompra; ?>" />
                            <span>Margem (%) <span><input style="width: 3em;" id="margemLucro" name="margemLucro" type="text" value="<?php echo $result->margemLucro; ?>" maxlength="3" size="2" />
                                    <strong><span style="color: red" id="errorAlert"></span><strong>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="precoVenda" class="control-label">Preço de Venda (R$)<span class="required">*</span></label>
                        <div class="controls">
                            <input id="precoVenda" class="money" data-affixes-stay="true" data-thousands="" data-decimal="." type="text" name="precoVenda" value="<?php echo $result->precoVenda; ?>" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="estoque" class="control-label">Estoque<span class="required">*</span></label>
                        <div class="controls">
                            <input style="width: 5em;"  type="number" name="estoque" value="<?php echo $result->estoque; ?>" />
                            <select style="width: 23%;" id="unidade" name="unidade">
                            <option disabled selected>Medida</option>
                                <?php if (!$resultMedida) {
                                    echo '<option disabled selected>Sem madidas cadastradas</option>';
                                }
                                
                                foreach ($resultMedida as $r) {
                                    if($result->idUnidade == $r->idMedida){
                                    echo "<option value='$r->idMedida' selected >$r->descricaoMedida</option>";
                                }else{
                                    echo "<option value='$r->idMedida' >$r->descricaoMedida</option>";
                                }
                            }
                                ?>
                        </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="estoqueMinimo" class="control-label">Estoque Mínimo</label>
                        <div class="controls">
                            <input style="width: 5em;" id="estoqueMinimo" type="number" name="estoqueMinimo" value="<?php echo $result->estoqueMinimo; ?>" />
                            <input style="width: 20%;" placeholder="Localização" id="local" type="text" name="local" value="<?php echo set_value('local'); ?>" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label for="observacao" class="control-label">Obs<span class="required"></span></label>
                        <div class="controls">
                            <textarea rows="4" cols="50" id="observacao" name="observacao" value="<?php echo set_value('observacao'); ?>"><?php echo $result->observacao; ?></textarea>
                        </div>
                    </div>
                  <!--  <div class="control-group">
                        <label class="control-label">Tipo de Movimento</label>
                        <div class="controls">
                            <label for="entrada" class="btn btn-default" style="margin-top: 5px;">Entrada
                                <input type="checkbox" id="entrada" name="entrada" class="badgebox" value="1" <?= ($result->entrada == 1) ? 'checked' : '' ?>>
                                <span class="badge">&check;</span>
                            </label>
                            <label for="saida" class="btn btn-default" style="margin-top: 5px;">Saída
                                <input type="checkbox" id="saida" name="saida" class="badgebox" value="1" <?= ($result->saida == 1) ? 'checked' : '' ?>>
                                <span class="badge">&check;</span>
                            </label>
                        </div>
                    </div> -->

                    <div class="control-group">
                        <label for="dataVencimento" class="control-label">Data de Vencimento </label>
                        <div class="controls">
                            <input id="dataVencimento" type="date" name="dataVencimento" value="<?php echo ($result->dataVencimento != '' || NULL) ? date("Y-m-d",  strtotime($result->dataVencimento)) : date('Y-m-d'); ?>" />
                            <input id="ativaVencimento"  type="checkbox">
                        </div>
                    </div>

                </div>
                </div>
                    <div class="form-actions">
                        <div class="span12">
                            <div class="span6 offset3" style="display: flex;justify-content: center">
                                <button type="submit" class="button btn btn-primary" style="max-width: 160px">
                                    <span class="button__icon"><i class="bx bx-sync"></i></span><span class="button__text2">Atualizar</span></button>
                                <a href="<?php echo base_url() ?>index.php/produtos" id="" class="button btn btn-mini btn-warning">
                                    <span class="button__icon"><i class="bx bx-undo"></i></span><span class="button__text2">Voltar</span></a>
                            </div>
                        </div>
                    </div>


                </form>
            

        </div>
    </div>
</div>


<script src="<?php echo base_url() ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/maskmoney.js"></script>
<script type="text/javascript">

    //verificador preenchimento do preço e margem de lucro

    function calcLucro(precoCompra, margemLucro) {
        var precoVenda = (precoCompra * margemLucro / 100 + precoCompra).toFixed(2);
        return precoVenda;
    }
    $("#precoCompra").focusout(function() {
        if ($("#precoCompra").val() == '0.00' && $('#precoVenda').val() != '') {
            $('#errorAlert').text('Você não pode preencher valor de compra e depois apagar.').css("display", "inline").fadeOut(6000);
            $('#precoVenda').val('');
            $("#precoCompra").focus();
        } else {
            $('#precoVenda').val(calcLucro(Number($("#precoCompra").val()), Number($("#margemLucro").val())));
        }
    });

    $("#margemLucro").keyup(function() {
        this.value = this.value.replace(/[^0-9.]/g, '');
        if ($("#precoCompra").val() == null || $("#precoCompra").val() == '') {
            $('#errorAlert').text('Preencher valor da compra primeiro.').css("display", "inline").fadeOut(5000);
            $('#margemLucro').val('');
            $('#precoVenda').val('');
            $("#precoCompra").focus();

        } else if (Number($("#margemLucro").val()) >= 0) {
            $('#precoVenda').val(calcLucro(Number($("#precoCompra").val()), Number($("#margemLucro").val())));
        } else {
            $('#errorAlert').text('Não é permitido número negativo.').css("display", "inline").fadeOut(5000);
            $('#margemLucro').val('');
            $('#precoVenda').val('');
        }
    });

    $('#precoVenda').focusout(function() {
        if (Number($('#precoVenda').val()) < Number($("#precoCompra").val())) {
            $('#errorAlert').text('Preço de venda não pode ser menor que o preço de compra.').css("display", "inline").fadeOut(6000);
            $('#precoVenda').val('');
            if ($("#margemLucro").val() != "" || $("#margemLucro").val() != null) {
                $('#precoVenda').val(calcLucro(Number($("#precoCompra").val()), Number($("#margemLucro").val())));
            }
        }

    });

    $(document).ready(function() {

        //Select com buscador
        $('#categorias').select2();
        $('#marcasAgrotec').select2();
        $('#tipoMarca').select2();
        $('#unidade').select2();

        //auto complete produto

        $("#produto").autocomplete({
            source: "<?php echo base_url(); ?>index.php/produtos/autoCompleteProduto",
            minLength: 1,
            close: function(ui) {
                if (ui.item.id == null) ui.target.value = '';
            },
            select: function(event, ui) {
                if (ui.item.id == null)
                    $('#produto_id').val(v);
                else {
                    $("#produto_id").val(ui.item.codigo);

                    $('.addclient').hide();
                }
            }
        });
        $("#adNotaFiscal").autocomplete({
            source: "<?php echo base_url(); ?>index.php/produtos/autoCompleteNotaFiscal",
            minLength: 1,
            close: function(ui) {
                if (ui.label == 'Adicionar nota fiscal...') ui.target.value = '';
            },
            select: function(event, ui) {
                if (ui.item.label == 'Adicionar nota fiscal...')
                    $('.addclient').show();
                else {
                    $("#adNotaFiscal_id").val(ui.item.notaFiscal);
 
                    $('.addclient').hide();
                }
            }
        });

        $(".money").maskMoney();
       


        $('#formProduto').validate({
            rules: {
                descricao: {
                    required: true
                },
                unidade: {
                    required: true
                },
                precoCompra: {
                    required: true
                },
                precoVenda: {
                    required: true
                },
                estoque: {
                    required: true
                }
            },
            messages: {
                descricao: {
                    required: 'Campo Requerido.'
                },
                unidade: {
                    required: 'Campo Requerido.'
                },
                precoCompra: {
                    required: 'Campo Requerido.'
                },
                precoVenda: {
                    required: 'Campo Requerido.'
                },
                estoque: {
                    required: 'Campo Requerido.'
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
        })
   
     $('#ativaVencimento').click(function(){
            if($('#ativaVencimento').is(":checked")){
               $("#dataVencimento").attr("readonly", false);
            }
            else if($('#ativaVencimento').is(":not(:checked)")){
               $("#dataVencimento").attr("readonly", true);
            }
        }); 



    $(document).on('change', '#marcasAgrotec', function() {
        var marca_id = $(this).val();
        if (marca_id != null) {

            selectMarca(marca_id);
        }
    });

    function selectMarca(marca_id) {
        // console.log(marca_id);
        $.ajax({
            url: '<?= base_url() ?>index.php/produtos/consultaTipoProduto/',
            method: 'POST',
            success: function(data) {
                var data = JSON.parse(data);
                optTipo = "<option disabled selected>Selecione o produto</option>";
                for (var i = 0; i < data.length; i++) {

                    // console.log(data[i]['marca']);
                    if (marca_id == data[i]['idMarca']) {
                        optTipo += "<option value=" + data[i]['idTipo'] + ">" + data[i]['descricaoProduto'] + "</option>";
                        $('#tipoMarca').html(optTipo);
                    }else if (!data) {
                        optTipo = "<option value=''>Marca sem produtos cadastrados</option>";
                        $('#tipoMarca').html(optTipo);
                    }
                }
            }
        });
    }

    const barCode = document.getElementById("produto");
        const myInput = document.querySelector("#descricao");
        const imgLogo = document.querySelector("#imageLogo");
        const image_x = document.getElementById('imgLogo');
        const marcas = document.getElementById('marcasAgrotec');

        barCode.addEventListener('change', buscaProdutos);

        function buscaProdutos() {

            myInput.value = "";
            $('#imgLogo').remove();


            var v = barCode.value;
            $.ajax({
                url: 'consultaProduto/' + v,
                success: function(data) {
                    $('#produto_id').val(v);
                    if (data != 0) {
                        var dados = JSON.parse(data);
                        console.log(dados);
                        var json = dados;

                        if (json.description) {
                            $('#descricao').val(dados.description);
                        }
                        if (json.brand.picture) {
                            var logoLink = json.brand.picture.split('/');
                            var logo = (logoLink[0] == 'https:') ? json.brand.picture : 'https://api.cosmos.bluesoft.com.br/' + json.brand.picture;
                            const image = document.createElement("img");
                            image.src = logo;
                            imgLogo.appendChild(image).setAttribute("id", "imgLogo");

                        }
                        $('#produto_id').val(v);
                        /* $('#description').val(json.description);
                         $('#marca').append(json.brand.name);
                         $('#avg_price').append(json.avg_price);
                         $('#updated_at').append(json.updated_at);
                         $('#barcode_image').attr('src', json.barcode_image);
                         $('#img').attr('src', logo);*/


                    }
                }
            });

        }
    });
</script>
