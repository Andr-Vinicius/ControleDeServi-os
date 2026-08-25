<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function loginStatus(): void
    {
        $this->viewOnly('auth/login', [
            'error'   => $_SESSION['login_error'] ?? null,
            'success' => $_SESSION['login_success'] ?? null,
        ]);
        unset($_SESSION['login_error'], $_SESSION['login_success']);
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = (new User())->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['login_error'] = 'Email ou Senha inválido';
            $this->redirect('/');
            return;
        }

        // Criação de uma nova sessão para evitar fixação de sessão
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['user_name'] = $user['name'];
        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        $_SESSION = []; // Limpa as sessões da memória relacionada ao login
        session_destroy();
        session_start();
        header('Location: ' . BASE_URL . '/');
        exit;
    }


    public function registerStatus(): void
    {
        // old - Manter os dados preenchidos no formulário caso haja algum erro
        $this->viewOnly('auth/register', [
            'error' => $_SESSION['register_error'] ?? null,
            'old'   => $_SESSION['register_old'] ?? [],
        ]);
        unset($_SESSION['register_error'], $_SESSION['register_old']);
    }

    public function register(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            $_SESSION['register_error'] = 'Preencha todos os campos obrigatórios';
            $_SESSION['register_old'] = ['name' => $name, 'email' => $email];
            $this->redirect('/register');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['register_error'] = 'Informe um email válido';
            $_SESSION['register_old'] = ['name' => $name, 'email' => $email];
            $this->redirect('/register');
            return;
        }

        $userModel = new User();

        if ($userModel->emailExists($email)) {
            $_SESSION['register_error'] = 'Este email já está cadastrado';
            $_SESSION['register_old'] = ['name' => $name, 'email' => $email];
            $this->redirect('/register');
            return;
        }

        $userModel->create($name, $email, $password);

        $_SESSION['login_success'] = 'Usuário cadastrado com sucesso! Faça o login.';
        $this->redirect('/');
    }
}
