<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastrar Novo Usuário</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body class="auth-page">
    <div class="auth-box">
        <h1>Cadastrar Novo Usuário</h1>
        <p class="auth-p">Preencha os dados para criar sua conta</p>

        <?php if (!empty($error)): ?>
            <p class="alert alert-error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/register">
            <span class="auth-span">Nome</span>
            <input type="text" name="name" placeholder="Nome completo" required
                   value="<?= htmlspecialchars($old['name'] ?? '') ?>">
            <span class="auth-span">Email</span>
            <input type="email" name="email" placeholder="email@email.com" required
                   value="<?= htmlspecialchars($old['email'] ?? '') ?>">
            <span class="auth-span">Senha</span>
            <input type="password" name="password" placeholder="**********" required minlength="6">

            <div class="auth-actions">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
                <a href="<?= BASE_URL ?>/" class="link-cadastrar">Já tenho uma conta</a>
            </div>
        </form>
    </div>
</body>
</html>