<?= $this->extend('public/template') ?>

<?= $this->section('content') ?>
<?php
$evento = $evento ?? [];
?>

<div class="container-fluid vh-100 d-flex flex-column">
    <div class="row flex-grow-1 align-items-stretch">
        <div class="col-xxl-8 col-lg-8 p-5 d-flex flex-column justify-content-center text-center" id="colum_1">
            <h1><?= esc($evento[0]['titulo']) ?></h1>
           <?= esc($evento[0]['descricao']) ?>
            <hr class="mb-4">
            <div class="alert alert-warning alert-dismissible fade show fw-medium text-start" role="alert">
                Escolha uma opção de compra e coloque a quantidade de entradas desejada.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
           <?php foreach ($evento as $eventoItem): ?>
               <div class="row">
                  <?php foreach ($eventoItem['variacoes'] as $variacao):
                     if ($variacao['encerra'] >= date('Y-m-d H:i:s') && $variacao['status'] == 1) :
                        ?>
                         <div class="col-xxl-4 col-md-6 shadow-lg">
                             <form action="/participantes/<?= $evento[0]['slug'] ?>?id=<?= $variacao['id'] ?>"
                                   method="post" enctype="multipart/form-data">

                                 <input type="hidden" value="<?= $evento[0]['id'] ?>" name="idEvento">
                                 <input type="hidden" value="<?= $evento[0]['empresa_id'] ?>" name="idEmpresa">
                                 <input type="hidden" value="<?= $evento[0]['titulo'] ?>" name="titulo_evento">
                                 <input type="hidden" value="<?= $variacao['preco'] ?>" name="precoVariacao">
                                 <input type="hidden" value="<?= $eventoItem['id'] ?>" name="idVariacao">

                                 <div class="card mb-3">
                                     <div class="card-body">
                                         <h5 class="card-title">
                                            <?= esc($variacao['nome']) ?>
                                         </h5>
                                         <p class="description_card">
                                            <?= esc($variacao['descricao']) ?>
                                         </p>
                                         <span class="badge text-bg-danger">10 restantes</span>
                                         <div class="text-center mt-3 d-flex flex-column align-items-center">
                                             <input type="number" class="form-control qtd-input" value="0" min="<?= $variacao['minimo'] ?>"
                                                    max="<?= $variacao['maximo'] ?>"
                                                    style="max-width: 100px;" aria-describedby="qtdHelp" name="qtd"
                                                    id="qtd">
                                             <label for="qtd" class="text-muted mt-2">Insira valores entre <?= $variacao['minimo'] ?> e
                                                <?= $variacao['maximo'] ?>.</label>
                                         </div>
                                         <div class="valor">
                                             <h3>R$ <?= number_format($variacao['preco'], 2, ',', '.') ?></h3>
                                         </div>
                                     </div>
                                     <div class="card-footer">
                                         <div class="text-end">
                                             <button type="submit" class="btn btn-primary comprar-btn">Comprar
                                             </button>
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
        <div class="col-xxl-4 col-lg-4 p-4 d-flex justify-content-center align-items-center flex-column" id="colum_2">
            <h1><?= esc($evento[0]['titulo']) ?></h1>
           <?= esc($evento[0]['descricao']) ?>
            <!--            <img src="https://placehold.co/400x400" alt="" class="img-fluid rounded-2" loading="lazy">-->
        </div>
    </div>
</div>


<?= $this->endSection() ?>

