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
                <th></th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($eventos as $evento) : ?>
                <tr>
                    <td><?= $evento['id'] ?></td>
                    <td>
                        <a href="/evento/<?= $evento['slug'] ?>" target="_blank">
                           <?= $evento['titulo'] ?></a>
                    </td>
                    <td>
                        <?php
                        $builderVariacoes = $variacoesModel->where('evento_id', $evento['id']);
                        $variacoes = $builderVariacoes->findAll();
                        foreach ($variacoes as $variacao) {
                            echo '<hr>';
                            echo $variacao['id'] . '<br>';
                            echo $variacao['titulo'] . '<br>';
                            echo $variacao['descricao'] . '<br>';
                            echo $variacao['valor'] . '<br>';
                            echo $variacao['quantidade'] . '<br>';
                            echo $variacao['data_inicio'] . '<br>';
                            echo $variacao['data_fim'] . '<br>';
                        }
                        ?>
                    </td>
                    <td>
                        <a href="/admin/eventos/editar/<?= $evento['id'] ?>" class="btn btn-primary"><i
                                    class="mdi mdi-pencil"></i></a>
                        <a href="/admin/eventos/deletar/<?= $evento['id'] ?>" class="btn btn-danger"><i
                                    class="mdi mdi-delete"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?= $pager->links() ?>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<?= $this->endSection() ?>

