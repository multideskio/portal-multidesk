<?= $this->extend('public/template') ?>
<?= $this->section('content') ?>
<?php $evento = $evento[0] ?? []; ?>
<div class="container py-5">
    <div class="row g-4">
        <!-- Coluna Esquerda: Resumo do Evento -->
        <div class="col-md-6">
            <div class="p-4 rounded-4 shadow-sm" style="background-color: #2a2a2a; color: #eaeaea;">
                <h1 class="fw-bold mb-2"><?= esc($evento['titulo']) ?></h1>
                <p class="text-soft"><?= $evento['descricao'] ?></p>
               <?php if (!empty($evento['meta'])):
                  $meta = json_decode($evento['meta'], true);
                  if (!empty($meta['duracao'])): ?>
                      <p class="small text-soft"><?= esc($meta['duracao']) ?></p>
                  <?php endif; ?>
               <?php endif; ?>
                <hr class="border-secondary">
                <p class="small">Escolha a quantidade desejada.</p>
            </div>
        </div>
        <!-- Coluna Direita: Formulário de Variações -->
        <div class="col-md-6">
            <form action="/participantes/<?= esc($evento['slug']) ?>" method="post">
                <input type="hidden" name="idEvento" value="<?= $evento['id'] ?>">
                <input type="hidden" name="idEmpresa" value="<?= $evento['empresa_id'] ?>">
                <input type="hidden" name="titulo_evento" value="<?= esc($evento['titulo']) ?>">
                <div class="d-flex flex-column gap-4">
                   <?php foreach ($evento['variacoes'] as $variacao): ?>
                      <?php if ($variacao['encerra'] >= date('Y-m-d H:i:s') && $variacao['status'] == 1): ?>
                           <div class="p-4 rounded-4 shadow-sm ticket-card">
                               <div class="d-flex justify-content-between align-items-center mb-2">
                                   <h5 class="mb-0"><?= esc($variacao['nome']) ?></h5>
                                   <span class="badge badge-restante">10 restantes</span>
                               </div>
                               <p class="text-soft mb-2"><?= esc($variacao['descricao']) ?></p>
                               <div class="d-flex align-items-center gap-3 mt-3">
                                   <label for="qtd_<?= $variacao['id'] ?>" class="mb-0">Qtd:</label>
                                   <input
                                           type="number"
                                           name="qtd[<?= $variacao['id'] ?>]"
                                           min="0"
                                           max="<?= $variacao['maximo'] ?>"
                                           value="0"
                                           required
                                           class="qtd-input"
                                           data-preco="<?= $variacao['preco'] ?>"
                                           data-target="subtotal_<?= $variacao['id'] ?>">
                                   <span class="ms-auto ticket-price">
            <span id="subtotal_<?= $variacao['id'] ?>">R$ <?= number_format($variacao['preco'], 2, ',', '.') ?></span>
        </span>
                               </div>
                           </div>

                      <?php endif; ?>
                   <?php endforeach; ?>
                </div>
                <div class="text-end mt-5">
                    <button type="submit" class="btn btn-light btn-lg btn-continuar">Continuar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('form');
        const inputs = document.querySelectorAll('.qtd-input');

        // Atualiza o subtotal por variação
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                const preco = parseFloat(input.dataset.preco);
                const qtd = parseInt(input.value) || 0;
                const targetId = input.dataset.target;
                const target = document.getElementById(targetId);
                const subtotal = preco * qtd;

                if (target) {
                    target.innerText = subtotal.toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    });
                }
            });
        });

        // Bloqueia envio se tudo for 0
        form.addEventListener('submit', (e) => {
            let peloMenosUm = false;

            inputs.forEach(input => {
                const qtd = parseInt(input.value) || 0;
                if (qtd > 0) {
                    peloMenosUm = true;
                }
            });

            if (!peloMenosUm) {
                e.preventDefault();
                alert('Selecione ao menos uma variação de ingresso.');
            }
        });
    });
</script>
<?= $this->endSection() ?>
