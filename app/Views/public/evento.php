<?= $this->extend('public/template') ?>
<?= $this->section('content') ?>
<?php
$evento = $evento ?? [];
?>
<div class="hero">
    <div class="hero-inner text-center">
        <h1><?= esc($evento[0]['titulo']) ?></h1>
    </div>
</div>
<div class="container mt-5 p-5">
    <div class="row">
        <div class="card shadow-lg border-0" id="card-evento">
            <div class="card-body mb-5">
                <div class="align-self-center text-center mb-5 mt-5" id="info-evento">
                    <h2 class="text-center"><?= esc($evento[0]['titulo']) ?></h2>
                    <div>Descrição do evento</div>
<!--                    <img src="https://placehold.co/1920x300" alt="Imagem do evento" class="img-fluid rounded-2"-->
<!--                         style="max-width: 300px;" loading="lazy">-->
                </div>
               <?php foreach ($evento as $eventoItem): ?>
                   <div class="row">
                      <?php foreach ($eventoItem['variacoes'] as $variacao):
                         if($variacao['encerra'] >= date('Y-m-d H:i:s') && $variacao['status'] == 1 ) :
                         ?>
                          <div class="col-md-4 mb-3">
                              <form action="/participantes/<?= $evento[0]['slug'] ?>?id=<?= $variacao['id'] ?>" method="post" enctype="multipart/form-data">
                                  <input type="hidden" value="<?= $evento[0]['id'] ?>" name="idEvento">
                                  <input type="hidden" value="<?= $evento[0]['empresa_id'] ?>" name="idEmpresa">
                                  <input type="hidden" value="<?= $evento[0]['titulo'] ?>" name="titulo_evento">
                                  <input type="hidden" value="<?= $variacao['preco'] ?>" name="precoVariacao">
                                  <div class="card h-100">
                                      <div class="card-body d-flex flex-column text-center">
                                          <h5 class="card-title fw-bold text-dark">
                                             <?= esc($variacao['nome']) ?>
                                          </h5>
                                          <p class="card-text">
                                             <?= esc($variacao['descricao']) ?>
                                          </p>
<!--                                          <p>-->
<!--                                              <b>Encerramento:</b><br>--><?php //= date('d/m/Y H:i', strtotime($variacao['encerra'])) ?>
<!--                                          </p>-->
                                          <label for="qtd1" class="form-label">
                                              <span class="badge bg-primary">Restam: 10</span>
                                          </label>
                                          <input type="number" id="qtd1" name="qtd" class="form-control shadow-sm" min="<?= $variacao['minimo'] ?>" max="<?= $variacao['maximo'] ?>" value="0" aria-describedby="qtdHelp">
                                          <small id="qtdHelp" class="form-text text-muted">
                                              Insira valores entre 1 e 10.
                                          </small>
                                          <input type="hidden" value="<?= $eventoItem['id'] ?>" name="idVariacao">
                                          <div class="text-center mt-3">
                                              <p class="fw-bold" style="font-size: 1.5rem; line-height: 2rem;">
                                                  <span style="font-weight: 900; font-size: 32px; font-family: 'Nunito', sans-serif">R$ <?= number_format($variacao['preco'], 2, ',', '.') ?></span>
                                              </p>
                                              <span class="badge text-bg-dark">Total disponível: 100</span>
                                          </div>
                                      </div>
                                      <div class="card-footer text-end mt-auto">
                                          <div class="d-grid gap-2">
                                              <button type="submit" class="btn btn-primary text-white fw-bold">Comprar agora</button>
                                          </div>
                                      </div>
                                  </div>
                              </form>
                          </div>
                       <?php endif; ?>
                      <?php endforeach; ?>
                   </div>
               <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

