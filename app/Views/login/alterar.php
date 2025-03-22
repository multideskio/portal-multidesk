<?= $this->extend('login/template') ?>
<?= $this->section('css') ?>
<style>
    .alert{
        font-size: 14px;
        margin-bottom: -10px;
    }
    input[readonly] {
        background-color: #f3f2f7;
        cursor: text;
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<form id="signInForm" class="geex-content__authentication__form">
   <h2 class="geex-content__authentication__title">Alterar Senha 👋</h2>
   <div class="geex-content__authentication__form-group">
      <label for="emailSignIn">E-mail de Recuperação</label>
      <input type="email" id="emailSignIn" name="emailSignIn" readonly>
      <i class="uil-envelope"></i>
   </div>
   <div class="geex-content__authentication__form-group">
      <div class="geex-content__authentication__label-wrapper">
         <label for="loginPassword">Nova Senha</label>
      </div>
      <input type="password" id="loginPassword" name="loginPassword" placeholder="Digite sua nova senha" required>
      <i class="uil-eye toggle-password-type"></i>
   </div>
   <div class="geex-content__authentication__form-group">
      <div class="geex-content__authentication__label-wrapper">
         <label for="loginPasswordConfirm">Confirmar Nova Senha</label>
      </div>
      <input type="password" id="loginPasswordConfirm" name="loginPasswordConfirm"
             placeholder="Digite novamente sua nova senha" required>
      <i class="uil-eye toggle-password-type"></i>
   </div>
   <div class="alert alert-danger" style="display: none" id="passwordAlertMessage"></div>
   <button type="submit" class="geex-content__authentication__form-submit">Alterar Senha</button>
   <div class="geex-content__authentication__form-footer">
      Lembrou sua senha? <a href="/login">Entrar</a>
   </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    const emailParam = getQueryParam('email');
    const email = document.getElementById('emailSignIn');
    if (emailParam) {
        email.value = emailParam;
    }

    const passwordField = document.getElementById("loginPassword");
    const passwordConfirmField = document.getElementById("loginPasswordConfirm");
    const passwordAlertMessage = document.getElementById("passwordAlertMessage");
    const signInForm = document.getElementById("signInForm");

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

    function validateForm(password, passwordConfirm, termsCheckbox) {
        if (password !== passwordConfirm) {
            alert('As senhas não conferem. Por favor, tente novamente.');
            return false;
        }
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

    signInForm.addEventListener("submit", (event) => {
        event.preventDefault();

        const email = document.getElementById("emailSignIn").value.trim();
        const password = passwordField.value.trim();
        const passwordConfirm = passwordConfirmField.value.trim();
        const token = getPartUrl();


        if (!validateForm(password, passwordConfirm)) {
            return;
        }
        const formData = new FormData();
        formData.append("email", email);
        formData.append("password", password);
        formData.append("token", token);

        for (const pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
    })
</script>
<?= $this->endSection() ?>
