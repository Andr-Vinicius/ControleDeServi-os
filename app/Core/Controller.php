<?php
namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        require BASE_PATH . '/app/Views/layouts/header.php';
        require BASE_PATH . '/app/Views/' . $view . '.php';
        require BASE_PATH . '/app/Views/layouts/footer.php';
    }

    protected function viewOnly(string $view, array $data = []): void
    {
        extract($data);
        require BASE_PATH . '/app/Views/' . $view . '.php';
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    protected function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('/');
        }
    }

    protected function flash(string $type, string $text): void
    {
        $_SESSION['flash_message'] = ['type' => $type, 'text' => $text];
    }
}