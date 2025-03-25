<?= $this->extend('admin/template') ?>
<?= $this->section('css') ?>

<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="geex-content__wrapper">
    <div class="geex-content__section-wrapper">
        <div class="geex-content__section geex-content__form">
            <form id="form-curso">

                <div class="geex-content__form__single">
                    <h4 class="geex-content__form__single__label">Default Input</h4>
                    <label for="geex-input1">Titulo do curso</label>
                    <div class="geex-content__form__single__box mb-20">
                        <input placeholder="Insert amount" class="form-control" id="geex-input1">
                    </div>

                    <label for="descricao">Descrição do curso</label>
                    <div class="geex-content__form__single__box mb-20">
                        <textarea name="descricao" id="descricao" cols="30" rows="10"></textarea>
                    </div>

                    <p>Variações</p>
                    <div id="variacoes">
                        <div class="geex-content__form__single__box mb-20">
                            <input placeholder="Insert amount" class="form-control" id="geex-input1">
                            <input placeholder="Insert amount" class="form-control" id="geex-input2">
                            <input placeholder="Insert amount" class="form-control" id="geex-input3">
                            <input placeholder="Insert amount" class="form-control" id="geex-input4">
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <div class="geex-content__widget">
        <div class="geex-content__section geex-content__form">
            <button type="button" class="btn btn-primary">SALVAR</button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

