<?= $this->extend('public/template') ?>
<?= $this->section('content') ?>

<?php
$session = session();
$carrinho = $session->get('carrinho') ?? [];
$participantes = $session->get('participantes') ?? [];
$itens = $carrinho['itens'] ?? [];
$total = $carrinho['total'] ?? 0;
?>
<style>
    /* NAV TABS */
    .nav-tabs {
        border: none;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #bbb;
        background: transparent;
        padding: 10px 20px;
        transition: all 0.2s;
        font-weight: 500;
    }

    .nav-tabs .nav-link:hover {
        color: #fff;
    }

    .nav-tabs .nav-link.active {
        color: #000;
        background-color: #fff;
        border-radius: 10px 10px 0 0;
        font-weight: bold;
    }

    /* CONTEÚDO DAS ABAS */
    .tab-content {
        background-color: #1e1e1e;
        border-radius: 0 0 12px 12px;
        padding: 20px;
        margin-top: -1px;
    }

    /* CAMPOS DO FORMULÁRIO */
    .form-control,
    .form-select {
        background-color: #2c2c2c;
        color: #fff;
        border: 1px solid #444;
        border-radius: 10px;
        padding: 10px 14px;
    }

    .form-control::placeholder {
        color: #aaa;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        background-color: #2c2c2c;
        color: #fff;
        box-shadow: none;
    }

    .form-label {
        font-weight: 500;
        color: #eaeaea;
    }

    /* BOTÕES */
    .btn-success {
        background-color: #198754;
        border-color: #198754;
        font-weight: bold;
        border-radius: 12px;
        padding: 12px;
    }

    .btn-success:hover {
        background-color: #157347;
        border-color: #146c43;
    }

    .btn-outline-secondary {
        color: #bbb;
        border-color: #444;
    }

    .btn-outline-secondary:hover {
        background-color: #333;
        border-color: #555;
        color: #fff;
    }

    .btn-outline-light {
        border-color: #555;
        color: #fff;
    }

    .btn-outline-light:hover {
        background-color: #444;
        color: #fff;
    }

    /* RESUMO DO PEDIDO */
    .list-group-item {
        background-color: transparent;
        border: none;
        color: #eee;
    }

    .text-soft {
        color: #999;
    }

    /* AJUSTE RESPONSIVO */
    @media (max-width: 768px) {
        .tab-content {
            padding: 16px;
        }
    }
</style>


<div class="container py-5 text-light">
    <div class="row g-4">
        <!-- Coluna esquerda -->
        <div class="col-md-8">
            <h2 class="fw-bold mb-4">💳 Finalizar Pagamento</h2>

            <!-- Botão de login com Google -->
            <div class="mb-4">
                <a href="/login-google" class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center">
                    <img src="/assets/img/icon/google.svg" alt="Google" width="20" class="me-2">
                    Entrar com Google
                </a>
            </div>

            <form id="formCheckout" method="post" action="/checkout/processar">

                <!-- Dados do cliente -->
                <div class="mb-4">
                    <h5 class="fw-bold">Dados do Cliente</h5>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Nome completo</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CPF</label>
                            <input type="text" name="cpf" class="form-control cpf-mask" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone" class="form-control phone-mask" required>
                        </div>
                    </div>
                </div>

                <!-- Endereço -->
                <div class="mb-4">
                    <h5 class="fw-bold">Endereço</h5>

                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label class="form-label">CEP</label>
                            <input type="text" name="cep" class="form-control cep-mask" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rua</label>
                            <input type="text" name="rua" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Número</label>
                            <input type="text" name="numero" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bairro</label>
                            <input type="text" name="bairro" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cidade</label>
                            <input type="text" name="cidade" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">UF</label>
                            <input type="text" name="uf" class="form-control" maxlength="2" required>
                        </div>
                    </div>
                </div>

                <!-- Cupom de desconto -->
                <div class="mb-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnMostrarCupom">Adicionar cupom de desconto</button>
                    <div class="mt-2 d-none" id="areaCupom">
                        <input type="text" name="cupom" class="form-control" placeholder="Digite seu cupom">
                    </div>
                </div>

                <!-- Método de pagamento -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Forma de Pagamento</h5>
                    <ul class="nav nav-tabs" id="tabPagamento" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#cartao">Cartão de Crédito</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#pix">
                                Pix
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content bg-dark p-4 rounded-bottom">
                        <!-- Cartão -->
                        <div class="tab-pane fade show active" id="cartao">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label">Número do cartão</label>
                                    <input type="text" name="numero_cartao" class="form-control card-mask">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Nome impresso</label>
                                    <input type="text" name="nome_cartao" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Validade (MM/AA)</label>
                                    <input type="text" name="validade" class="form-control validade-mask">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">CVV</label>
                                    <input type="text" name="cvv" class="form-control cvv-mask">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Parcelas</label>
                                    <select name="parcelas" class="form-select">
                                        <option value="1">1x sem juros</option>
                                        <option value="2">2x sem juros</option>
                                        <option value="3">3x sem juros</option>
                                        <!-- ... -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Pix -->
                        <div class="tab-pane fade" id="pix">
                            <p class="text-soft" style="font-size: 1.5em">Ao finalizar o pedido, um QR Code será gerado.</p>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="metodo_pagamento" id="metodoPagamento" value="cartao">

                <!-- Botão de confirmar pedido -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-lg">Confirmar Pedido</button>
                </div>
            </form>
        </div>

        <!-- Coluna direita: resumo do pedido -->
        <div class="col-md-4">
            <div class="p-4 rounded-4 shadow-sm" style="background-color: #2a2a2a;">
                <h4 class="fw-bold mb-3"><?= esc($carrinho['evento_titulo'] ?? 'Evento') ?></h4>
                <h6 class="mb-3 text-soft">Resumo da compra</h6>

                <ul class="list-group list-group-flush">
                   <?php foreach ($itens as $id => $item): ?>
                       <li class="list-group-item bg-transparent text-light d-flex justify-content-between align-items-center border-0">
                           <div>
                               <strong><?= esc($item['nome']) ?></strong><br>
                               <small><?= esc($item['quantidade']) ?>x R$ <?= number_format($item['preco'], 2, ',', '.') ?></small>
                           </div>
                           <form action="/remover-item" method="post" class="ms-2">
                               <input type="hidden" name="id_variacao" value="<?= $id ?>">
                               <button type="submit" class="btn btn-sm btn-outline-danger">✕</button>
                           </form>
                       </li>
                   <?php endforeach; ?>
                    <li class="list-group-item bg-transparent text-light fw-bold d-flex justify-content-between border-top mt-2 pt-2">
                        Total:
                        <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    const metodoPagamentoInput = document.getElementById('metodoPagamento');

    document.querySelectorAll('#tabPagamento button').forEach(btn => {
        btn.addEventListener('click', function () {
            metodoPagamentoInput.value = this.dataset.bsTarget === '#pix' ? 'pix' : 'cartao';
        });
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $('.cpf-mask').mask('000.000.000-00');
    $('.phone-mask').mask('(00) 00000-0000');
    $('.cep-mask').mask('00000-000');
    $('.card-mask').mask('0000 0000 0000 0000');
    $('.validade-mask').mask('00/00');
    $('.cvv-mask').mask('000');
</script>
<?= $this->endSection() ?>
