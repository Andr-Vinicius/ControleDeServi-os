<?php
namespace App\Models;

use App\Core\Database;

class User
{
    public function findByEmail(string $email): ?array
    {
        $stmt = Database::getInstance()->prepare(
            'SELECT * FROM user WHERE email = :email AND ativo = 1'
        );

        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = Database::getInstance()->prepare('SELECT * FROM user WHERE id_user = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function emailExists(string $email): bool
    {
        $stmt = Database::getInstance()->prepare('SELECT id_user FROM user WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return (bool) $stmt->fetch();
    }

    public function create(string $name, string $email, string $password): int
    {
        $stmt = Database::getInstance()->prepare(
            'INSERT INTO user (name, email, password, created_at, ativo)
             VALUES (:name, :email, :password, NOW(), 1)'
        );
        $stmt->execute([
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return (int) Database::getInstance()->lastInsertId();
    }
}
