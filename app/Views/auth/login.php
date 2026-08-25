<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-box">
        <h1>Sistema de Controle de Serviços</h1>
        <p class="auth-p">Faça login para acessar o sistema</p>

        <?php if (!empty($success)): ?>
            <p class="alert alert-success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <p class="alert alert-error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/login">
            <span class="auth-span">Email</span>
            <input type="email" name="email" placeholder="email@email.com" required autofocus>
            <span class="auth-span">Senha</span>
            <input type="password" name="password" placeholder="**********" required>

            <div class="auth-actions">
                <button type="submit" class="btn btn-primary">Entrar</button>
                <a href="<?= BASE_URL ?>/register" class="link-cadastrar">Cadastrar usuário</a>
            </div>
        </form>
    </div>
</body>
</html>
