<?= $this->extend('login/template') ?>
<?= $this->section('content') ?>
<form id="signInForm" class="geex-content__authentication__form" method="POST">
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

<?= $this->section('js') ?>
<script>
    const signInForm = document.getElementById("signInForm");

    signInForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const email = document.getElementById('emailSignIn').value.trim();

        const formData = new FormData();
        formData.append("email", email);

        Swal.fire({
            title: 'Aguarde...',
            text: 'Verificando o e-mail...',
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false
        })

        try{
            const response = await fetch(base_url+'recuperar', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.messages.error || 'Erro ao realizar cadastro. Tente novamente.');
            }
            const responseData = await response.json();

            Swal.fire({
                title: '📫 Email enviado!',
                text: 'Enviamos um link para redefinir sua senha!',
                icon: 'success',
                confirmButtonText: 'Ok'
            }).then((result) => {
                window.location.href = `${base_url}`;
            });

        }catch (error) {
            Swal.fire({
                title: 'Erro!',
                text: error.message,
                icon: 'error',
                confirmButtonText: 'Ok'
            })
            console.error('Erro:', error);
        }
    })
</script>
<?= $this->endSection() ?>
