<?= $this->extend('login/template') ?>
<?= $this->section('css') ?>
    <style>
    </style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <form id="signInForm" class="geex-content__authentication__form">
        <h2 class="geex-content__authentication__title">Crie Sua Conta 👋</h2>
        <div class="geex-content__authentication__form-group">
            <label for="emailSignIn">Seu Email</label>
            <input type="email" id="emailSignIn" name="emailSignIn" placeholder="Digite Seu Email" required="">
            <i class="uil-envelope"></i>
        </div>
        <div class="geex-content__authentication__form-group">
            <div class="geex-content__authentication__label-wrapper">
                <label for="loginPassword">Senha</label>
            </div>
            <input type="password" id="loginPassword" name="loginPassword" placeholder="Senha" required="">
            <i class="uil-eye toggle-password-type"></i>
        </div>
        <div class="geex-content__authentication__form-group">
            <div class="geex-content__authentication__label-wrapper">
                <label for="loginPasswordConfirm">Confirme a Senha</label>
            </div>
            <input type="password" id="loginPasswordConfirm" name="loginPasswordConfirm" placeholder="Senha"
                   required="">
            <i class="uil-eye toggle-password-type"></i>
        </div>
        <div class="alert alert-danger" style="display: none" id="passwordAlertMessage">
        </div>


        <div class="geex-content__authentication__form-group custom-checkbox">
            <input type="checkbox" class="geex-content__authentication__checkbox-input" id="rememberMe" name="aceito">
            <label class="geex-content__authentication__checkbox-label" for="rememberMe">Ao criar uma conta, você
                concorda
                com os nossos <a href="#">termos &amp; condições e Política de Privacidade</a></label>
            <div class="invalid-feedback">Você precisa aceitar os termos e condições.</div>
            <div class="alert alert-danger" style="display: none" id="termsAlertMessage">

            </div>
        </div>
        <button type="submit" class="geex-content__authentication__form-submit">Cadastrar</button>
        <!--    <span class="geex-content__authentication__form-separator">Ou</span>-->
        <!--    <div class="geex-content__authentication__form-social">-->
        <!--        <a href="#" class="geex-content__authentication__form-social__single">-->
        <!--            <img src="./assets/img/icon/google.svg" alt="">Google-->
        <!--        </a>-->
        <!--        <a href="#" class="geex-content__authentication__form-social__single">-->
        <!--            <svg width="15" height="19" viewBox="0 0 15 19" fill="none" xmlns="http://www.w3.org/2000/svg">-->
        <!--                <path d="M10.9133 0H11.0427C11.1465 1.2826 10.6569 2.24096 10.062 2.93497C9.47815 3.62419 8.67872 4.29264 7.38574 4.19122C7.29949 2.92698 7.78985 2.0397 8.38403 1.34729C8.93508 0.701997 9.94535 0.127781 10.9133 0ZM14.8274 13.3499V13.3859C14.464 14.4864 13.9457 15.4296 13.3132 16.3048C12.7358 17.0995 12.0282 18.1689 10.7647 18.1689C9.67302 18.1689 8.94786 17.4669 7.82898 17.4477C6.64541 17.4285 5.99452 18.0347 4.91238 18.1872H4.54341C3.74877 18.0722 3.10747 17.4429 2.64027 16.8759C1.26264 15.2003 0.19806 13.0361 0 10.2664V9.4526C0.0838563 7.47039 1.04701 5.85876 2.32721 5.0777C3.00285 4.66241 3.93166 4.30861 4.96589 4.46674C5.40913 4.53543 5.86195 4.68717 6.25887 4.83731C6.63503 4.98186 7.10542 5.23822 7.55106 5.22464C7.85294 5.21586 8.15322 5.05853 8.4575 4.94752C9.34877 4.62567 10.2225 4.2567 11.3741 4.43001C12.7581 4.63925 13.7404 5.25419 14.3474 6.20297C13.1766 6.94809 12.251 8.07096 12.4091 9.98848C12.5497 11.7303 13.5624 12.7493 14.8274 13.3499Z"-->
        <!--                      fill="black"></path>-->
        <!--            </svg>-->
        <!--            Apple-->
        <!--        </a>-->
        <!--    </div>-->
        <div class="geex-content__authentication__form-footer">
            já tem uma conta? <a href="/login">Entrar</a>
        </div>
    </form>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
    <script>
        const passwordField = document.getElementById("loginPassword");
        const passwordConfirmField = document.getElementById("loginPasswordConfirm");
        const passwordAlertMessage = document.getElementById("passwordAlertMessage");
        const signInForm = document.getElementById("signInForm");
        const termsCheckbox = document.getElementById("rememberMe");
        const termsAlertMessage = document.getElementById("termsAlertMessage");

        const passwordRegex = /^(?=.*[a-zA-Z])(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{6,}$/;

        passwordConfirmField.addEventListener("input", () => {
            const password = passwordField.value.trim();
            const passwordConfirm = passwordConfirmField.value.trim();

            if (!passwordRegex.test(password)) {
                showAlert(passwordAlertMessage, 'A senha deve ter no mínimo 6 caracteres, incluindo pelo menos 1 letra e 1 caractere especial.', true);
            } else if (password !== passwordConfirm) {
                showAlert(passwordAlertMessage, 'As senhas não conferem. Por favor, tente novamente.', true);
            } else {
                showAlert(passwordAlertMessage, '', false);
            }
        });

        signInForm.addEventListener("submit", async (event) => {
            event.preventDefault();
            const email = document.getElementById("emailSignIn").value.trim();
            const password = passwordField.value.trim();
            const passwordConfirm = passwordConfirmField.value.trim();
            if (!validateForm(password, passwordConfirm, termsCheckbox)) {
                return;
            }
            const formData = new FormData();
            formData.append("email", email);
            formData.append("password", password);
            try {
                const response = await fetch('/registrar', {
                    method: 'POST',
                    body: formData
                });
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Erro ao realizar cadastro. Tente novamente.');
                }
                const responseData = await response.json();
                window.location.href = `/confirmar/${responseData.token}`;
            } catch (error) {
                console.error('Erro:', error);
                alert(error.message);
            }
        });

        function validateForm(password, passwordConfirm, termsCheckbox) {
            if (password !== passwordConfirm) {
                alert('As senhas não conferem. Por favor, tente novamente.');
                return false;
            }
            if (!termsCheckbox.checked) {
                termsCheckbox.focus(); // Garante que o foco seja aplicado ao checkbox
                showAlert(
                    termsAlertMessage,
                    'Aceite os termos de uso para continuar.',
                    true
                );
                termsCheckbox.focus(); // Adiciona o foco no checkbox
                return false;
            }
            showAlert(termsAlertMessage, '', false);
            return true;
        }

        function showAlert(element, message, show) {
            if (show) {
                element.textContent = message;
                element.style.display = 'block';
                element.classList.add('error');
            } else {
                element.textContent = '';
                element.style.display = 'none';
                element.classList.remove('error');
            }
        }
    </script>
<?= $this->endSection() ?>