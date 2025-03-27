<?= $this->extend('public/template') ?>
<?= $this->section('content') ?>
<?php
$carrinho = $carrinho ?? [];
?>

<div class="hero">
    <div class="hero-inner text-center">
        <h1>Dados do participante</h1>
    </div>
</div>

<form action="/participantes" method="post" class="bg-light">
    <div class="container mt-5">
        <div class="row">
            <div class="card p-0">
                <div class="card-body mb-5 p-5">
                    <h3 class="mt-3">Informe os dados do participante</h3>
                    <p>Preencha com os dados do participante</p>
                   <?php for ($i = 0; $i < $carrinho['data']['qtd']; $i++): ?>
                       <div class="row mt-5 shadow-lg p-3 mb-5 bg-white rounded">
                           <input type="hidden" name="idVariacao[]" value="<?= $carrinho['data']['idVariacao'] ?>">
                           <input type="hidden" name="idEvento[]" value="<?= $carrinho['data']['idEvento'] ?>">
                           <div class="col-md-4">
                               <label for="nome-<?= $i ?>">Nome</label>
                               <input type="text" class="form-control" id="nome-<?= $i ?>" name="nome[]"
                                      placeholder="Nome do participante" required>
                           </div>
                           <div class="col-md-4">
                               <label for="email-<?= $i ?>">Email</label>
                               <input type="email" class="form-control" id="email-<?= $i ?>" name="email[]"
                                      placeholder="Email do participante" required>
                           </div>
                           <div class="col-md-4">
                               <label for="telefone-<?= $i ?>">Telefone</label>
                               <input type="text" class="form-control" id="telefone-<?= $i ?>" name="telefone[]"
                                      placeholder="Telefone do participante" required>
                           </div>
                       </div>
                   <?php endfor; ?>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary text-white btn-lg">Continuar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
