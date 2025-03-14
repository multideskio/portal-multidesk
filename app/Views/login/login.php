<?= $this->extend('login/template') ?>

<?= $this->section('css') ?>
    <style>
        .geex-content__authentication__form-submit {
            background: #5840ff !important;
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <!-- LOGIN FORM START -->
    <form id="signInForm" class="geex-content__authentication__form">
        <h2 class="geex-content__authentication__title">Entre na Sua Conta 👋</h2>
        <div class="geex-content__authentication__form-group">
            <label for="emailSignIn">Seu E-mail</label>
            <input type="email" id="emailSignIn" name="emailSignIn" placeholder="Digite seu e-mail"
                   required>
            <i class="uil-envelope"></i>
        </div>
        <div class="geex-content__authentication__form-group">
            <div class="geex-content__authentication__label-wrapper">
                <label for="loginPassword">Sua Senha</label>
                <a href="/recuperar">Esqueceu a Senha?</a>
            </div>
            <input type="password" id="loginPassword" name="loginPassword" placeholder="Senha" required>
            <i class="uil-eye toggle-password-type"></i>
        </div>
        <div class="geex-content__authentication__form-group custom-checkbox">
            <input type="checkbox" class="geex-content__authentication__checkbox-input" id="rememberMe">
            <label class="geex-content__authentication__checkbox-label" for="rememberMe">Lembrar-me</label>
        </div>
        <button type="submit" class="geex-content__authentication__form-submit" id="entrar">Entrar</button>
        <span class="geex-content__authentication__form-separator">Ou</span>

        <!--                    SOCIAL SECTION START-->
        <div class="geex-content__authentication__form-social">
            <a href="#" class="geex-content__authentication__form-social__single">
                <img src="/assets/img/icon/google.svg" alt="">Google
            </a>
        </div>
        <!--                    SOCIAL SECTION END-->

        <div class="geex-content__authentication__form-footer">
            Não tem uma conta? <a href="/registrar">Cadastre-se</a>
        </div>
    </form>
    <!-- LOGIN FORM END -->
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="/assets/js/login.js"></script>
<?= $this->endSection() ?>
