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
    const inputs = document.querySelectorAll('.geex-content__authentication__form-group__code input');

    inputs.forEach((input, idx) => {
        // Tratamento para digitação normal
        input.addEventListener('input', (e) => {
            // Verifica se mais de um caractere foi inserido
            if (e.target.value.length > 1) {
                const values = e.target.value.split('');
                inputs.forEach((inp, index) => inp.value = values[index] || '');
                inputs[values.length - 1]?.focus();
            } else {
                // Avança ou volta entre os campos na digitação
                if (e.target.value && idx < inputs.length - 1) {
                    inputs[idx + 1].focus();
                } else if (!e.target.value && idx > 0) {
                    inputs[idx - 1].focus();
                }
            }
        });

        // Tratamento para colagem
        input.addEventListener('paste', (e) => {
            // Obtém o texto colado
            const pasteData = (e.clipboardData || window.clipboardData).getData('text');
            if (pasteData.length === inputs.length) {
                // Distribui os valores colados de forma proporcional
                const values = pasteData.split('');
                inputs.forEach((inp, index) => inp.value = values[index]);
                inputs[inputs.length - 1].focus();
                e.preventDefault();
            }
        });
    });
</script>

<script>
    // Função para obter o valor de um parâmetro na URL
    function getQueryParam(key) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(key);
    }

    // Função para mascarar o e-mail
    function maskEmail(email) {
        if (!email) return ""; // Retorna vazio se o e-mail não for fornecido

        const atIndex = email.indexOf('@'); // Índice do símbolo '@'
        if (atIndex === -1) return email; // Retorna o e-mail original caso não seja válido

        const firstPart = email.substring(0, 4); // Pegando os primeiros 4 caracteres
        const domain = email.substring(atIndex); // Pegando o domínio (incluindo @)
        const maskedLength = Math.max(atIndex - 4, 0); // Calculando quantos '*' devem ser colocados

        return firstPart + "*".repeat(maskedLength) + domain;
    }

    // Obtendo o e-mail do parâmetro na URL
    const email = getQueryParam('email');
    const maskedEmail = maskEmail(email);

    // Renderizando no elemento HTML
    const emailElement = document.querySelector('.verification-number');
    if (emailElement) {
        emailElement.textContent = maskedEmail;
    }
</script>
<?= $this->endSection() ?>
