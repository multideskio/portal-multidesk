<?= $this->extend('admin/template') ?>
<?= $this->section('css') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<form autocomplete="off" class="needs-validation" novalidate method="post" action="/api/v1/eventos"
      enctype="multipart/form-data" id="form-create-curso">
    <div class="row">
        <div class="col-xl-8">
           <?php
           if (session()->getFlashdata('success')) {
              echo '<div class="alert alert-success">' . session()->getFlashdata('success') . '</div>';
           }
           if (session()->getFlashdata('error')) {
              echo '<div class="alert alert-danger">' . session()->getFlashdata('error') . '</div>';
           }
           ?>
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="titulo-curso" class="form-label fw-bold">Nome do evento ou curso presencial</label>
                        <input type="text" class="form-control form-control-lg" id="titulo-curso" name="titulo"
                               placeholder="Título nome do evento ou curso presencial" required minlength="5" maxlength="80">
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
                        <textarea name="endereco" id="endereco" cols="30" rows="5" class="form-control" required
                                  minlength="10" maxlength="255"></textarea>
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
                    <div class="alert alert-primary">As variações permitem oferecer ingressos com valores diferentes,
                        atendendo às suas necessidades.
                        Por exemplo, você pode disponibilizar uma opção com material incluso a um valor específico e
                        outra com preço diferenciado sem o material.
                    </div>
                    <div class="text-end mb-3">
                        <button type="button" class="btn btn-primary add-variation-btn" id="add-variation-btn"><i
                                    class="mdi mdi-plus"></i>
                            Adicionar Variação
                        </button>
                    </div>
                    <div class="row" id="variacao-card">
                        <div class="col-xl-6">
                            <div class="card mb-1 shadow-lg border-black border-1">
                                <div class="card-body p-3 mt-3">
                                    <div class="mb-3">
                                        <label for="titulo-variacao">Titulo da variação</label>
                                        <input type="text" class="form-control" name="titulo_variacao[]"
                                               id="titulo-variacao" placeholder="Lote 0" required minlength="5">
                                    </div>
                                    <div class="mb-3">
                                        <label for="desc-variacao">Descrição da variação</label>
                                        <textarea type="text" class="form-control" name="desc_variacao[]" id="desc-variacao" placeholder="Descreva essa variação"></textarea>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <label for="num_min">Quantidade minima por pessoa</label>
                                            <input type="number" class="form-control" name="num_min[]" id="num_min" placeholder="1" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="num_max">Quantidade Maxima por pessoa</label>
                                            <input type="number" class="form-control" name="num_max[]" id="num_max" placeholder="10" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <label for="date_var_start">Início de vendas</label>
                                            <input type="datetime-local" class="form-control"
                                                   name="date_var_start[]" id="date_var_start"
                                                   placeholder="Selecione a data e hora" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="date_var_end">Final de vendas</label>
                                            <input type="datetime-local" class="form-control"
                                                   name="date_var_end[]" id="date_var_end"
                                                   placeholder="Selecione a data e hora" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <label for="valor">Valor</label>
                                            <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" name="valor[]" id="valor" placeholder="100,00" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="quantidade">Quantidade disponivel</label>
                                            <input type="number" id="quantidade" name="quantidade[]"
                                                   class="form-control" value="1" required>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch form-switch-lg" dir="ltr">
                                        <input type="checkbox" class="form-check-input" id="customSwitchsizelg"
                                               checked="" name="ativo[]" value="1">
                                        <label class="form-check-label" for="customSwitchsizelg">Ativo</label>
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

        <div class="col-xl-4">
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

            <!-- Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Capa para a página</h5>
                </div>
                <div class="card-body">

                    <!-- Preview da imagem recortada -->
                    <img id="preview-cover-image" src="#" alt="Pré-visualização da imagem"
                         class="img-fluid mb-3" style="display: none; max-height: 200px;">

                    <!-- Botão para selecionar imagem -->
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('cover-image').click()">
                        Selecionar imagem
                    </button>

                    <!-- Input original (sem name, usado apenas para selecionar) -->
                    <input type="file" id="cover-image" accept="image/*" style="display: none;">

                    <!-- Input hidden gerado automaticamente via JS com name="cover_image_base64" -->
                    <!-- Será criado dinamicamente dentro do <form> pelo JS, não precisa estar aqui -->

                </div>
            </div>


            <!-- Modal do Croppie -->
            <div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar imagem de capa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body text-center">
                            <div id="croppie-container"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="crop-button" class="btn btn-primary">Recortar imagem</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Datas de disponibilidade</h5>
                </div>
                <div class="card-body">
                    <label for="start_vendas" class="form-label">Data e hora de início</label>
                    <input type="datetime-local" name="start_vendas" id="start_vendas" class="form-control" required>

                    <label for="end_vendas" class="form-label mt-3">Data e hora de final</label>
                    <input type="datetime-local" name="end_vendas" id="end_vendas" class="form-control" required>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
</form>


<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"
        integrity="sha512-Gs+PsXsGkmr+15rqObPJbenQ2wB3qYvTHuJO6YJzPe/dTLvhy0fmae2BcnaozxDo5iaF8emzmCZWbQ1XXiX2Ig=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    let croppieInstance = null;

    const coverInput = document.getElementById('cover-image');
    const previewImage = document.getElementById('preview-cover-image');
    const cropModalElement = document.getElementById('cropModal');
    const cropModal = new bootstrap.Modal(cropModalElement);

    /**
     * Inicializa o Croppie com configurações fixas (proporção 1920x600)
     */
    function initializeCroppie(url) {
        const container = document.getElementById('croppie-container');

        if (croppieInstance) {
            croppieInstance.destroy();
            container.innerHTML = '';
        }

        croppieInstance = new Croppie(container, {
            viewport: { width: 640, height: 200, type: 'square' }, // proporção 3.2
            boundary: { width: 700, height: 300 },
            enableResize: false,
            enableZoom: true,
            enforceBoundary: true,
            enableOrientation: true
        });

        croppieInstance.bind({ url }).then(() => {
            croppieInstance.setZoom(0.7); // Zoom inicial
        }).catch(err => {
            console.error("Erro ao carregar imagem no Croppie:", err);
        });
    }

    /**
     * Resultado do recorte: salva base64 no input hidden e mostra preview
     */
    function handleCropResult(base64) {
        // Cria input hidden (se não existir)
        let hiddenInput = document.getElementById('cover-image-base64');

        if (!hiddenInput) {
            hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'cover_image_base64';
            hiddenInput.id = 'cover-image-base64';
            document.querySelector('#form-create-curso').appendChild(hiddenInput);
        }

        hiddenInput.value = base64;

        // Mostra preview no card
        previewImage.src = base64;
        previewImage.style.display = 'block';

        console.log(base64);

        // Fecha modal
        cropModal.hide();
    }

    /**
     * Ao selecionar imagem
     */
    coverInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (!file) {
            console.warn("Nenhum arquivo selecionado");
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            const onModalShown = function () {
                initializeCroppie(e.target.result);
                cropModalElement.removeEventListener('shown.bs.modal', onModalShown);
            };

            cropModalElement.addEventListener('shown.bs.modal', onModalShown);
            cropModal.show();
        };

        reader.onerror = (e) => {
            console.error("Erro ao ler arquivo:", e);
        };

        reader.readAsDataURL(file);
    });

    /**
     * Botão "Recortar imagem"
     */
    document.getElementById('crop-button').addEventListener('click', () => {
        if (!croppieInstance) return;

        croppieInstance.result({
            type: 'base64',
            size: { width: 1920, height: 600 }
        }).then(handleCropResult);
    });
</script>



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
            return `<div class="col-xl-6">
            <div class="card mb-1 shadow-lg border-black border-1">
                <div class="card-body p-3">
                    <a type="button" class="remove-card">
                        <i class="mdi mdi-trash-can-outline"></i>
                    </a>
                    <div class="mb-3">
                        <label for="titulo-variacao-${index}">Titulo da variação</label>
                        <input type="text" class="form-control" name="titulo_variacao[]" id="titulo-variacao-${index}" placeholder="Lote ${++index}" required>
                    </div>
                    <div class="mb-3">
                        <label for="desc-variacao-${index}">Descrição da variação</label>
                        <textarea type="text" class="form-control" name="desc_variacao[]" id="desc-variacao-${index}" placeholder="Descreva essa variação"></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label for="num_min-${index}">Compra miníma</label>
                            <input type="number" class="form-control" name="num_min[]" id="num_min-${index}" placeholder="1" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="num_max-${index}">Compra máxima</label>
                            <input type="number" class="form-control" name="num_max[]" id="num_max-${index}" placeholder="10" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label for="date_var_start-${index}">Inicio de venda</label>
                            <input type="datetime-local" class="form-control" name="date_var_start[]" id="date_var_start-${index}" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="date_var_end-${index}">Final de vendas</label>
                            <input type="datetime-local" class="form-control" name="date_var_end[]" id="date_var_end-${index}" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label for="valor-${index}">Valor</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span><input type="text" class="form-control" name="valor[]" id="valor-${index}" required placeholder="100,00">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label for="quantidade-${index}">Quantidade disponível</label>
                            <input type="number" id="quantidade-${index}" name="quantidade[]" class="form-control" value="1">
                        </div>
                    </div>
                    <div class="form-check form-switch form-switch-lg" dir="ltr">
                        <input type="checkbox" class="form-check-input" id="customSwitchsizelg-${index}"
                               checked="" name="ativo[]" value="1">
                        <label class="form-check-label" for="customSwitchsizelg-${index}">Ativo</label>
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

