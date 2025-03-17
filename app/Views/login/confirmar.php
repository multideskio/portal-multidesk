<?= $this->extend('login/template') ?>
<?= $this->section('content') ?>
<form id="signInForm" class="geex-content__authentication__form">
    <h2 class="geex-content__authentication__title">Verificação em Duas Etapas 👋</h2>
    <p class="geex-content__authentication__desc">Enviamos um código de verificação para o seu e-mail. Digite o código
        do seu e-mail no campo abaixo. <span class="verification-number"></span>
    </p>
    <div class="geex-content__authentication__form-group">
        <label for="verificationCode1">Digite seu código de segurança de 6 dígitos</label>
        <div class="geex-content__authentication__form-group__code">
            <input type="text" id="verificationCode1" name="verificationCode1" maxlength="1" required="">
            <input type="text" id="verificationCode2" name="verificationCode2" maxlength="1" required="">
            <input type="text" id="verificationCode3" name="verificationCode3" maxlength="1" required="">
            <input type="text" id="verificationCode4" name="verificationCode4" maxlength="1" required="">
            <input type="text" id="verificationCode5" name="verificationCode5" maxlength="1" required="">
            <input type="text" id="verificationCode6" name="verificationCode6" maxlength="1" required="">
        </div>
    </div>
    <button type="submit" class="geex-content__authentication__form-submit">Verificar Minha Conta</button>
    <div class="geex-content__authentication__form-footer">
        Não recebeu o código? <a href="#">Reenviar</a>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    // Função Utilitária para Consultar Parâmetro na URL
    function getQueryParam(key) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(key);
    }

    // Função para Mascarar o Email
    function maskEmail(email) {
        if (!email) return ""; // Retorna vazio se o e-mail não for fornecido

        const atIndex = email.indexOf('@');
        if (atIndex === -1) return email; // Retorna o e-mail original caso não seja válido

        const firstPart = email.substring(0, 4); // Pegando os primeiros 4 caracteres
        const domain = email.substring(atIndex); // Pegando o domínio (incluindo @)
        const maskedLength = Math.max(atIndex - 4, 0);

        return firstPart + "*".repeat(maskedLength) + domain;
    }

    // Função para Obter o Código de Verificação
    function getVerificationCode() {
        const inputs = document.querySelectorAll('.geex-content__authentication__form-group__code input');
        return Array.from(inputs)
            .map(input => input.value)
            .join('');
    }

    // Função para Exibir Alertas de Status com Swal
    // Função para Exibir Alertas de Status com Swal
    function showAlert({ title, text, icon, confirmButton = true, timer = null }) {
        return Swal.fire({ // O 'return' foi adicionado
            title,
            text,
            icon,
            showConfirmButton: confirmButton,
            allowOutsideClick: !confirmButton,
            timer,
        });
    }

    // Função Assíncrona para Enviar o Formulário
    async function submitForm(event) {
        event.preventDefault();

        const email = getQueryParam('email');
        const token = window.location.pathname.split('/').pop();
        const verificationCode = getVerificationCode();

        if (!email || verificationCode.length !== 6) {
            showAlert({
                title: 'Erro',
                text: 'Os dados fornecidos estão incompletos! Verifique e tente novamente.',
                icon: 'error',
            });
            return;
        }

        // Dados para o Formulário
        const formData = new FormData();
        formData.append("email", email);
        formData.append("code", verificationCode);

        try {
            // Exibe o carregamento
            showAlert({
                title: 'Aguardando...',
                text: 'Processando seu cadastro',
                icon: 'info',
                confirmButton: false,
            });

            const response = await fetch(`${base_url}confirmar/${token}`, {
                method: 'POST',
                body: formData,
            });

            if (!response.ok) {
                throw new Error('Código inválido. Verifique e tente novamente.');
            }

            // Confirmação de Sucesso
            showAlert({
                title: 'Sucesso!',
                text: 'Seu cadastro foi confirmado com sucesso',
                icon: 'success',
                confirmButton: true,
                timer: null,
            }).then(() => {
                //window.location.href = `/login?confirm=true&email=${email}`;
            });
        } catch (error) {
            showAlert({
                title: 'Erro',
                text: error.message || 'Erro desconhecido ao processar sua solicitação',
                icon: 'error',
            });
        }
    }

    // Inicialização do Código
    function initializeForm() {
        const email = getQueryParam('email');
        const maskedEmail = maskEmail(email);

        const emailElement = document.querySelector('.verification-number');
        if (emailElement) {
            emailElement.textContent = maskedEmail;
        }

        const signInForm = document.querySelector('#signInForm');
        if (signInForm) {
            signInForm.addEventListener('submit', submitForm);
        }
    }

    // Inicializa o Código na Carga da Página
    document.addEventListener('DOMContentLoaded', initializeForm);

    // Tratamento dos Inputs do Código de Verificação
    document.querySelectorAll('.geex-content__authentication__form-group__code input').forEach((input, idx, inputs) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length > 1) {
                const values = e.target.value.split('');
                inputs.forEach((inp, index) => inp.value = values[index] || '');
                inputs[values.length - 1]?.focus();
            } else {
                if (e.target.value && idx < inputs.length - 1) inputs[idx + 1].focus();
                else if (!e.target.value && idx > 0) inputs[idx - 1].focus();
            }
        });

        input.addEventListener('paste', (e) => {
            const pasteData = (e.clipboardData || window.clipboardData).getData('text');
            if (pasteData.length === inputs.length) {
                const values = pasteData.split('');
                inputs.forEach((inp, index) => inp.value = values[index]);
                inputs[inputs.length - 1].focus();
                e.preventDefault();
            }
        });
    });
</script>
<?= $this->endSection() ?>
