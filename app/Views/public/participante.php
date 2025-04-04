<?= $this->extend('public/template') ?>
<?= $this->section('content') ?>
<?php
/** @var array $evento */
$carrinho = $carrinho ?? [];
$itens = $carrinho['itens'] ?? [];
$total = $carrinho['total'] ?? 0;
$camposExtras = $evento['campos'] ?? []; // <-- ✅ Adicione esta linha
?>

    <style>
        @media (min-width: 768px) {
            .left-scrollable {
                max-height: 80vh;
                overflow-y: auto;
                padding-right: 8px;
            }

            .sticky-summary {
                position: sticky;
                top: 30px;
            }
        }

        @media (max-width: 767px) {
            .left-scrollable {
                max-height: none;
                overflow-y: visible;
            }

            .sticky-summary {
                position: static;
            }
        }

        .ticket-card {
            background-color: #2a2a2a;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
        }

        .text-soft {
            color: rgba(255, 255, 255, 0.7);
        }
    </style>

<div class="container py-5">
    <div class="row g-4">
        <!-- Coluna esquerda -->
        <div class="col-md-8">
            <h2 class="fw-bold mb-4">👤 Participantes</h2>
            <div class="pe-2">
               <form action="/confirmar-participantes/<?= esc($evento['slug']) ?>" method="post">

                   <?php foreach ($itens as $key => $item): ?>
                      <?php for ($i = 1; $i <= $item['quantidade']; $i++): ?>
                           <div class="ticket-card mb-4">
                               <input type="hidden" name="idVariacao[]" value="<?= esc($item['id_variacao']) ?>">
                               <input type="hidden" name="idEvento[]" value="<?= esc($carrinho['evento_id']) ?>">

                               <div class="mb-3">
                                   <h5 class="mb-1"><?= esc($item['nome']) ?> - Participante <?= $i ?></h5>
                                   <p class="text-soft small">
                                      <?= esc($item['descricao']) ?> | R$<?= number_format($item['preco'], 2, ',', '.') ?>
                                   </p>
                               </div>

                               <div class="row g-3">
                                   <div class="col-md-4">
                                       <label for="nome-<?= $key ?>-<?= $i ?>">Nome</label>
                                       <input id="nome-<?= $key ?>-<?= $i ?>" type="text" name="nome[]" class="form-control bg-dark text-light border-0" required>
                                   </div>
                                   <div class="col-md-4">
                                       <label for="email-<?= $key ?>-<?= $i ?>">Email</label>
                                       <input id="email-<?= $key ?>-<?= $i ?>" type="email" name="email[]" class="form-control bg-dark text-light border-0" required>
                                   </div>
                                   <div class="col-md-4">
                                       <label for="tel-<?= $key ?>-<?= $i ?>">Telefone</label>
                                       <input id="tel-<?= $key ?>-<?= $i ?>" type="text" name="telefone[]" class="form-control bg-dark text-light border-0 telefone-mask" required>
                                   </div>
                               </div>

                              <?php if (!empty($camposExtras)): ?>
                                  <div class="row g-3 mt-3">
                                     <?php foreach ($camposExtras as $campo): ?>
                                        <?php
                                        $isRequired = isset($campo['required']) && $campo['required'] === true;
                                        $requiredAttr = $isRequired ? 'required' : '';
                                        ?>
                                         <div class="col-md-6">
                                             <label for="extra-<?= $campo['name'] ?>-<?= $i ?>"><?= esc($campo['label']) ?></label>

                                            <?php if ($campo['type'] === 'select'): ?>
                                                <select class="form-control bg-dark text-light border-0"
                                                        name="extras[<?= $key ?>][<?= $campo['name'] ?>][]"
                                                        id="extra-<?= $campo['name'] ?>-<?= $i ?>" <?= $requiredAttr ?>>
                                                    <option value="">Selecione</option>
                                                   <?php foreach ($campo['options'] as $opt): ?>
                                                       <option value="<?= esc($opt) ?>"><?= esc($opt) ?></option>
                                                   <?php endforeach; ?>
                                                </select>
                                            <?php else: ?>
                                                <input type="text"
                                                       class="form-control bg-dark text-light border-0"
                                                       name="extras[<?= $key ?>][<?= $campo['name'] ?>][]"
                                                       id="extra-<?= $campo['name'] ?>-<?= $i ?>" <?= $requiredAttr ?>>
                                            <?php endif; ?>
                                         </div>
                                     <?php endforeach; ?>
                                  </div>
                              <?php endif; ?>


                           </div>
                      <?php endfor; ?>
                   <?php endforeach; ?>

                    <button type="button" class="btn btn-outline-light btn-sm mb-3" id="preencherTodos">
                        Preencher todos os campos com os mesmos dados
                    </button>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-light btn-continuar">Continuar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Coluna direita -->
        <div class="col-md-4">
            <div class="p-4 rounded-4 shadow-sm sticky-summary" style="background-color: #2a2a2a;">
                <h4 class="fw-bold mb-3"><?= esc($carrinho['evento_titulo']) ?></h4>
                <h6 class="mb-3 text-soft">Resumo da compra</h6>

                <ul class="list-group list-group-flush">
                   <?php foreach ($itens as $item): ?>
                       <li class="list-group-item bg-transparent text-light d-flex justify-content-between border-0">
                          <?= esc($item['nome']) ?> (<?= $item['quantidade'] ?>x)
                           <span>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></span>
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
<!-- Input mask para telefone -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const botaoPreencher = document.getElementById('preencherTodos');

        if (botaoPreencher) {
            botaoPreencher.addEventListener('click', () => {
                const form = document.querySelector('form');
                const grupos = form.querySelectorAll('.ticket-card');

                if (grupos.length < 2) return;

                const valoresPadrao = {};

                // Captura os valores normalizados do primeiro grupo
                const camposOrigem = grupos[0].querySelectorAll('input, select');
                camposOrigem.forEach(el => {
                    let name = el.name;

                    // Remove índices extras[0][campo][] → extras[][campo][]
                    name = name.replace(/\[\d+\]/g, '[]');

                    if (name) valoresPadrao[name] = el.value;
                });

                // Aplica os valores nos demais grupos
                grupos.forEach((grupo, idx) => {
                    if (idx === 0) return;

                    const campos = grupo.querySelectorAll('input, select');
                    campos.forEach(el => {
                        let name = el.name;
                        const normalizado = name.replace(/\[\d+\]/g, '[]');

                        if (valoresPadrao[normalizado] !== undefined) {
                            el.value = valoresPadrao[normalizado];
                        }
                    });
                });
            });
        }

        // Máscara para telefone
        $('.telefone-mask').mask('(00) 00000-0000');
    });
</script>
<?= $this->endSection() ?>
