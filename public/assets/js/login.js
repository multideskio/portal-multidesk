document.getElementById("signInForm").addEventListener("submit", async (event) => {
    event.preventDefault(); // Previne o envio padrão do formulário

    const email = document.getElementById("emailSignIn").value;
    const password = document.getElementById("loginPassword").value;

    try {
        // Cria um objeto FormData para enviar os dados como multipart/form-data
        const formData = new FormData();
        formData.append("email", email);
        formData.append("password", password);

        // Requisição única para realizar login e criar a sessão
        const loginResponse = await fetch('login', {
            method: 'POST',
            body: formData
        });

        if (!loginResponse.ok) {
            throw new Error('Erro ao realizar login. Verifique suas credenciais.');
        }

        // Redireciona o usuário para a rota /admin após o sucesso
        window.location.href = '/admin';

    } catch (error) {
        // Exibe o erro no console ou na interface
        console.error('Erro:', error);
        alert(error.message);
    }
});

// const userData = localStorage.getItem('userData');
// if (userData) {
//     localStorage.removeItem('userData');
//     window.location.href = '/logout';
// }