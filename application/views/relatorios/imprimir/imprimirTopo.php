<?php if ($emitente[0]): ?>
    <div>
        <br>
        <div style="width: 60%; float: left; margin-top:-15px" class="float-left col-md-2">
            <img style="width: 180px" src="<?= convertUrlToUploadsPath($emitente[0]->url_logo) ?>" alt=""><br><br>
        </div>
        <div style="float: right">
            <b>EMPRESA: </b> <?= $emitente[0]->nome ?> <br> <b>CNPJ: </b> <?= $emitente[0]->cnpj ?><br>
            <b>ENDEREÇO: </b> <?= $emitente[0]->rua ?>, <?= $emitente[0]->numero ?>, <?= $emitente[0]->bairro ?>, <?= $emitente[0]->cidade ?> - <?= $emitente[0]->uf ?> <br>

            <?php if (isset($title)): ?>
                <b>RELATÓRIO: </b> <?= $title ?> <br>
            <?php endif ?>

            <?php if (isset($dataInicial)): ?>
                <b>DATA INICIAL: </b> <?= $dataInicial ?>
            <?php endif ?>

            <?php if (isset($dataFinal)): ?>
                <b>DATA FINAL: </b> <?= $dataFinal ?>
            <?php endif ?>
        </div>
    </div>
<?php endif ?>
