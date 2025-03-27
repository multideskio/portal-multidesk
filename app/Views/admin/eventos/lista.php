<?= $this->extend('admin/template') ?>

<?= $this->section('content') ?>
<?php
/**
 * @var array $eventos
 * @var object $variacoesModel
 */
?>
<div class="card">
    <div class="card-header">
    </div>
    <div class="card-body">
        <h5 class="card-title">Lista de eventos</h5>
        <table class="table table-striped" style="width: 100%">
            <thead>
            <tr>
                <th>id</th>
                <th>Titulo</th>
                <th>Varições</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($eventos['eventos'] as $evento) : ?>
                <tr>
                    <td><?= $evento['id'] ?></td>
                    <td>
                        <a href="/evento/<?= $evento['slug'] ?>" target="_blank" class="d-block">
                           <?= $evento['titulo'] ?>
                        </a>
                        <a href="/evento/<?= $evento['slug'] ?>" target="_blank" class="d-block text-muted">
                           <?= site_url("evento/" . $evento['slug']) ?>
                        </a>
                    </td>
                    <td>

                       <?php foreach ($evento['variacoes'] as $variacoes): ?>
                           <span class="mb-2">
                                <span class="badge badge-gradient-danger"> <?= $variacoes['nome'] ?> - <?= 'R$ ' . number_format($variacoes['preco'], 2, ',', '.') ?></span>
                           </span>
                       <?php endforeach; ?>
                    </td>
                    <td>
                        <a href="/admin/eventos/editar/<?= $evento['id'] ?>"
                           style="text-decoration: none; color: inherit;" title="Editar">
                            <i class="mdi mdi-pencil"></i></a>
                        <a href="/admin/eventos/deletar/<?= $evento['id'] ?>"
                           style="text-decoration: none; color: inherit;" title="Deletar">
                            <i class="mdi mdi-delete"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
       <?= $eventos['pager']->links() ?>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<?= $this->endSection() ?>

