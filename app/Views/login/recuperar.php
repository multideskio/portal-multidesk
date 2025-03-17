<?= $this->extend('login/template') ?>
<?= $this->section('content') ?>
<form id="signInForm" class="geex-content__authentication__form">
    <h2 class="geex-content__authentication__title">Esqueceu sua senha? 👋</h2>
    <p class="geex-content__authentication__desc">Por favor, insira o endereço de e-mail associado à sua conta e
        enviaremos um link para você redefinir sua senha.</p>
    <div class="geex-content__authentication__form-group">
        <label for="emailSignIn">Endereço de E-mail</label>
        <input type="email" id="emailSignIn" name="emailSignIn" placeholder="Digite seu e-mail" required="">
        <i class="uil-envelope"></i>
    </div>
    <button type="submit" class="geex-content__authentication__form-submit">Redefinir Senha</button>
    <a href="/login" class="geex-content__authentication__form-submit return-btn">Voltar ao Login</a>
</form>
<?= $this->endSection() ?>
