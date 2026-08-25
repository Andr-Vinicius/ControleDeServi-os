<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controle de Serviços</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>
<div class="app">
    <?php $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>
    <aside class="sidebar">
        <p class="logged-as">Logado como:<br><strong><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></strong></p>
        <nav>
            <a class="sidebar-item<?= $currentPath === BASE_URL . '/dashboard' ? ' active' : '' ?>" href="<?= BASE_URL ?>/dashboard">Inicio</a>
            <a class="sidebar-item<?= $currentPath === BASE_URL . '/service/create' ? ' active' : '' ?>" href="<?= BASE_URL ?>/service/create">Cadastrar Serviço</a>
            <a class="sidebar-item" href="<?= BASE_URL ?>/logout">Sair</a>
        </nav>
    </aside>
    <main class="content">