<?= $this->extend('admin/template') ?>
<?= $this->section('css') ?>

<?= $this->endSection() ?>
<?= $this->section('content') ?>
<form autocomplete="off" class="needs-validation" novalidate method="post" action="/api/v1/eventos" enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-9">
           <?php
           if (session()->getFlashdata('success')) {
              echo '<div class="alert alert-success">' . session()->getFlashdata('success'). '</div>';
           }
           if (session()->getFlashdata('error')) {
               echo '<div class="alert alert-danger">' . session()->getFlashdata('error'). '</div>';
           }
           ?>
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="titulo-curso" class="form-label fw-bold">Título do Curso</label>
                        <input type="text" class="form-control" id="titulo-curso" name="titulo"
                               placeholder="Título do Curso" required minlength="5" maxlength="80">
                        <div class="invalid-feedback">Por favor, preencha o campo título.</div>
                    </div>
                    <div class="mb-3">
                        <label for="ckeditor-description" class="form-label fw-bold">Descrição Detalhada</label>
                        <textarea name="description" id="ckeditor-description" class="form-control" rows="2"
                                  required></textarea>
                        <div class="invalid-feedback">Por favor, preencha o campo descrição.</div>
                    </div>
                </div>
            </div>
            <!-- end card -->
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="endereco" class="form-label fw-bold">Endereço completo</label>
                        <textarea name="endereco" id="endereco" cols="30" rows="5" class="form-control" required minlength="10" maxlength="255"></textarea>
                    </div>
                </div>
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-header fw-bold">
                    Variações
                </div>
                <!-- end card header -->
                <div class="card-body">
                    <div class="alert alert-primary">As variações permitem oferecer ingressos com valores diferentes, atendendo às suas necessidades.
                        Por exemplo, você pode disponibilizar uma opção com material incluso a um valor específico e
                        outra com preço diferenciado sem o material.</div>
                    <div class="text-end mb-3">
                        <button type="button" class="btn btn-primary add-variation-btn" id="add-variation-btn"><i
                                    class="mdi mdi-plus"></i>
                            Adicionar Variação
                        </button>
                    </div>
                    <div class="row" id="variacao-card">
                        <div class="col-lg-4">
                            <div class="card mb-1 shadow-lg border-black border-1">
                                <div class="card-body p-3 mt-3">
                                    <div class="mb-3">
                                        <label for="titulo-variacao">Titulo da variação</label>
                                        <input type="text" class="form-control" name="titulo_variacao[]"
                                               id="titulo-variacao">
                                    </div>
                                    <div class="mb-3">
                                        <label for="desc-variacao">Descrição da variação</label>
                                        <textarea type="text" class="form-control" name="desc_variacao[]"
                                                  id="desc-variacao">
                                        </textarea>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <label for="num_min">Minimo</label>
                                            <input type="number" class="form-control" name="num_min[]" id="num_min">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="num_max">Maximo</label>
                                            <input type="number" class="form-control" name="num_max[]" id="num_max">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <label for="date_var_start">Data e Hora de Início</label>
                                            <input type="datetime-local" class="form-control"
                                                   name="date_var_start[]" id="date_var_start"
                                                   placeholder="Selecione a data e hora">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="date_var_end">Data e Hora de Fim</label>
                                            <input type="datetime-local" class="form-control"
                                                   name="date_var_end[]" id="date_var_end"
                                                   placeholder="Selecione a data e hora">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <label for="valor">Valor</label>
                                            <input type="text" class="form-control" name="valor[]" id="valor">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="quantidade">Qtd</label>
                                            <input type="number" id="quantidade" name="quantidade[]"
                                                   class="form-control" value="1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end tab content -->
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->

        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Publicar</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <select name="status_curso" id="status_curso" class="form-select">
                            <option value="publicado" selected>Publicar</option>
                            <option value="rascunho">Rascunho</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div class="text-end mb-3">
                        <button type="submit" class="btn btn-success w-100">Salvar</button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <label for="categoria_id" class="card-title mb-0">Categoria</label>
                </div>
                <div class="card-body">
                    <select name="categoria_id" id="categoria_id" class="form-select">
                        <option value="evento">Evento</option>
                        <option value="curso">Curso</option>
                        <option value="palestra">Palestra</option>
                        <option value="outro">Outro</option>
                    </select>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Capa para a página</h5>
                </div>
                <div class="card-body">
                    <label for="cover-image" class="form-label">Imagem de capa</label>
                    <input type="file" id="cover-image" name="cover-image" class="form-control">
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Datas de disponibilidade</h5>
                </div>
                <div class="card-body">
                    <label for="start-datetime" class="form-label">Data e hora de início</label>
                    <input type="text" id="start-datetime" name="start_vendas" class="form-control flatpickr-input"
                           placeholder="Digite a data de início" data-provider="flatpickr"
                           data-date-format="d/m/Y" data-enable-time="true" data-default-date="today" readonly required>

                    <label for="end-datetime" class="form-label mt-3">Data e hora de final</label>
                    <input type="text" id="end-datetime" class="form-control flatpickr-input"
                           placeholder="Digite a data de final" name="end_vendas" data-provider="flatpickr"
                           data-date-format="d/m/Y" data-enable-time="true" readonly required>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
</form>


<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<!-- Validations -->
<script src="/assets/js/pages/form-validation.init.js"></script>

<!-- ckeditor -->
<script src="/assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js"></script>

<!-- dropzone js -->
<!--<script src="/assets/libs/dropzone/dropzone-min.js"></script>-->
<!--<script src="/assets/js/pages/ecommerce-product-create.init.js"></script>-->

<script>
    const ckClassicEditor = document.querySelectorAll("#ckeditor-description")
    ckClassicEditor.forEach(function () {
        ClassicEditor
            .create(document.querySelector('#ckeditor-description'))
            .then(function (editor) {
                editor.ui.view.editable.element.style.height = '200px';
            })
            .catch(function (error) {
                console.error(error);
            });
    });


    document.addEventListener("DOMContentLoaded", function () {

        // Selecionar o botão de adicionar card e o container onde os cards serão inseridos
        const addCardButton = document.getElementById("add-variation-btn");
        const variacaoCardContainer = document.querySelector("#variacao-card");

        // Função para gerar um novo card, incrementando os indexes
        let cardIndex = 0; // Para controlar o índice único de cada card criado

        function createCard(index) {
            return `<div class="col-lg-4">
            <div class="card mb-1 shadow-lg border-black border-1">
                <div class="card-body p-3">
                    <a type="button" class="remove-card">
                        <i class="mdi mdi-trash-can-outline"></i>
                    </a>
                    <div class="mb-3">
                        <label for="titulo-variacao-${index}">Titulo da variação</label>
                        <input type="text" class="form-control" name="titulo_variacao[]" id="titulo-variacao-${index}">
                    </div>
                    <div class="mb-3">
                        <label for="desc-variacao-${index}">Descrição da variação</label>
                        <textarea type="text" class="form-control" name="desc_variacao[]" id="desc-variacao-${index}"></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label for="num_min-${index}">Minimo</label>
                            <input type="number" class="form-control" name="num_min[]" id="num_min-${index}">
                        </div>
                        <div class="col-lg-6">
                            <label for="num_max-${index}">Maximo</label>
                            <input type="number" class="form-control" name="num_max[]" id="num_max-${index}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label for="date_var_start-${index}">Data de inicio</label>
                            <input type="datetime-local" class="form-control" name="date_var_start[]" id="date_var_start-${index}">
                        </div>
                        <div class="col-lg-6">
                            <label for="date_var_end-${index}">Data de fim</label>
                            <input type="datetime-local" class="form-control" name="date_var_end[]" id="date_var_end-${index}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label for="valor-${index}">Valor</label>
                            <input type="text" class="form-control" name="valor[]" id="valor-${index}">
                        </div>
                        <div class="col-lg-6">
                            <label for="quantidade-${index}">Qtd</label>
                            <input type="number" id="quantidade-${index}" name="quantidade[]" class="form-control" value="1">
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        }

        // Adicionar evento para criar um novo card ao clicar no botão
        addCardButton.addEventListener("click", function () {
            const newCard = createCard(cardIndex); // Criar o HTML do novo card
            variacaoCardContainer.insertAdjacentHTML("beforeend", newCard); // Adicionar ao container
            cardIndex++; // Incrementar o índice
        });

        // Delegação de eventos para remover cards
        variacaoCardContainer.addEventListener("click", function (e) {
            if (e.target.classList.contains("remove-card") || e.target.closest(".remove-card")) {
                const card = e.target.closest(".col-lg-4"); // Selecionar a div do card
                if (card) {
                    card.remove(); // Remover o card
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>

