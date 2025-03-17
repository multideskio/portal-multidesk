<?php
/** @var string $email */
/** @var string $nome */
$email = $email ?? '';
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email</title>
</head>
<body style="margin: 0; background-color: #f4f4f4; font-family: Arial, sans-serif; text-align: center; padding-top: 20px; padding-bottom: 20px;">

<!-- Central container do email -->
<div style="max-width: 600px; margin: 40px auto; background-color: #ffffff; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); border-radius: 8px; padding: 20px;">

    <!-- Logo -->
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="<?= base_url('/assets/img/logo-dark.svg')?>" alt="Logo da Empresa" style="max-width: 150px;">
    </div>

    <!-- Mensagem principal -->
    <h1 style="font-size: 18px; color: #5840ff; margin-bottom: 10px; font-weight: 900">
        Bem-vindo(a) <?= $nome ?? $email ?> 👋,</h1>
    <p style="font-size: 16px; color: #444444; margin: 10px 0; font-weight: 600;">
        Estamos muito felizes em ter você conosco! <br>
        Sua conta foi criada com sucesso e você já pode começar a usar nossa plataforma.
    </p>

    <!-- Mensagem secundária -->
    <p style="font-size: 14px; color: #666666; margin: 20px 0;">
        Para sua segurança, caso você não tenha criado uma conta conosco, por favor entre em contato com nosso suporte
        imediatamente.
    </p>

    <!-- Botão para contato ou ação -->
    <a href="<?= base_url('login') ?>"
       style="display: inline-block; background-color: #5840ff; color: #ffffff; text-decoration: none; font-size: 16px; font-weight: bold; padding: 12px 20px; border-radius: 5px; margin-top: 20px;">
        Acessar minha conta
    </a>

    <!-- Rodapé -->
    <p style="font-size: 12px; color: #999999; margin-top: 20px; border-top: 1px solid #eeeeee; padding-top: 10px;">
        &copy; <?= date('Y') ?>, Multidesk. Todos os direitos reservados.
    </p>
</div>
</body>
</html>