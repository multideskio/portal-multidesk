<?= $this->extend('public/template') ?>
<?= $this->section('content') ?>
<?php
$carrinho = $carrinho ?? [];
?>

<div class="container-fluid vh-100 d-flex flex-column">
    <div class="row flex-grow-1 align-items-stretch">
        <div class="col-xxl-8 col-lg-8 p-5 d-flex flex-column justify-content-center text-center" id="colum_1">
            <form action="/participantes" method="post">
                <h3 class="mt-3">Informe os dados do participante</h3>
                <p>Preencha com os dados do participante</p>
               <?php for ($i = 1; $i <= $carrinho['data']['qtd']; $i++): ?>
                   <div class="row shadow-lg p-3 mb-3 rounded shadow-white">
                       <input type="hidden" name="idVariacao[]" value="<?= $carrinho['data']['idVariacao'] ?>">
                       <input type="hidden" name="idEvento[]" value="<?= $carrinho['data']['idEvento'] ?>">
                       <h4 class="text-start">Participante <?= $i ?></h4>
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
                <div class="text-end">
                    <button type="submit" class="btn btn-primary text-white btn-lg">Continuar</button>
                </div>
            </form>
        </div>
        <div class="col-xxl-4 col-lg-4 p-5 d-flex flex-column text-center" id="colum_2">
            <div style="position: sticky; top: 10px; z-index: 10; background-color: #343a40;" class="p-3 rounded-2">
                <h1><?= esc($carrinho['data']['titulo_evento']) ?></h1>
                <h5 class="mt-3 text-start">Resumo de compra:</h5>
                <div class="table">
                    <table class="table table-striped table-hover table-bordered table-dark" style="font-size: 22px; color: #007bff;">
                        <tr>
                            <td>Quatidade:</td>
                            <td><?= esc($carrinho['data']['qtd']) ?></td>
                        </tr>
                        <tr>
                            <td>Valor unitário:</td>
                            <td>R$ <?= number_format($carrinho['data']['precoVariacao'], 2, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td>Valor total:</td>
                            <td>
                                R$ <?= number_format($carrinho['data']['precoVariacao'] * $carrinho['data']['qtd'], 2, ',', '.') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

   <?= $this->endSection() ?>
