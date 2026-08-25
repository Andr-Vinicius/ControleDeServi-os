<?php
namespace App\Models;

use App\Core\Database;

class Service
{
    // Reutilização de Query
    private function customSelect(): string
    {
        return "SELECT s.*, u.name AS user_name, u.email AS user_email,
                        CASE WHEN s.finished_at IS NULL THEN 'PENDENTE' ELSE 'FINALIZADO' END AS status
                 FROM service s
                 INNER JOIN user u ON u.id_user = s.user_id_user";
    }


    // Filtros Dinâmicos para Listagem de Serviços
    public function listFiltered(array $filters = []): array
    {
        $sql = $this->customSelect() . ' WHERE 1=1'; // 1=1 sempre retorna true, facilitando a adição de filtros dinâmicos
        $params = [];

        if (!empty($filters['descricao'])) {
            $sql .= ' AND s.description LIKE :descricao';
            $params['descricao'] = '%' . $filters['descricao'] . '%';
        }

        if (!empty($filters['usuario'])) {
            $sql .= ' AND u.name LIKE :usuario';
            $params['usuario'] = '%' . $filters['usuario'] . '%';
        }

        if (!empty($filters['status']) && in_array($filters['status'], ['PENDENTE', 'FINALIZADO'], true)) {
            $sql .= $filters['status'] === 'PENDENTE'
                ? ' AND s.finished_at IS NULL'
                : ' AND s.finished_at IS NOT NULL';
        }

        if (!empty($filters['data_inicio'])) {
            $sql .= ' AND DATE(s.created_at) >= :data_inicio';
            $params['data_inicio'] = $filters['data_inicio'];
        }

        if (!empty($filters['data_fim'])) {
            $sql .= ' AND DATE(s.created_at) <= :data_fim';
            $params['data_fim'] = $filters['data_fim'];
        }

        $sql .= ' ORDER BY s.created_at DESC';

        $stmt = Database::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = Database::getInstance()->prepare(
            $this->customSelect() . ' WHERE s.id_service = :id'
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    // Serviços Pendentes do Usuário 
    public function pendingByUser(int $userId, int $limit = 5): array
    {
        $stmt = Database::getInstance()->prepare(
            'SELECT id_service, description FROM service
             WHERE user_id_user = :user_id AND finished_at IS NULL
             ORDER BY created_at DESC LIMIT ' . (int) $limit
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    // Últimos Serviços do Usuário (independente do status)
    public function latestByUser(int $userId, int $limit = 5): array
    {
        $stmt = Database::getInstance()->prepare(
            'SELECT id_service, description FROM service
             WHERE user_id_user = :user_id
             ORDER BY created_at DESC LIMIT ' . (int) $limit
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    // Soma Agregada
    public function totalByUser(int $userId): float
    {
        $stmt = Database::getInstance()->prepare(
            'SELECT COALESCE(SUM(price), 0) AS total FROM service WHERE user_id_user = :user_id'
        );
        $stmt->execute(['user_id' => $userId]);
        return (float) $stmt->fetch()['total'];
    }

    // CRUD Básico
    public function create(string $description, float $price, int $userId): int
    {
        $stmt = Database::getInstance()->prepare(
            'INSERT INTO service (description, price, user_id_user, created_at)
             VALUES (:description, :price, :user_id, NOW())'
        );
        $stmt->execute([
            'description' => $description,
            'price'       => $price,
            'user_id'     => $userId,
        ]);

        return (int) Database::getInstance()->lastInsertId();
    }

    public function update(int $id, string $description, float $price): bool
    {
        $stmt = Database::getInstance()->prepare(
            'UPDATE service SET description = :description, price = :price WHERE id_service = :id'
        );
        return $stmt->execute([
            'description' => $description,
            'price'       => $price,
            'id'          => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = Database::getInstance()->prepare('DELETE FROM service WHERE id_service = :id');
        return $stmt->execute(['id' => $id]);
    }

    /**  Regras de Comissão
     *   - até R$ 250,00         -> 5%
     *   - acima de R$ 1.000,00  -> 10%
     *   - acima de R$ 10.000,00 -> 20%
     */
    public function calculateCommission(float $price): float
    {
        if ($price > 10000) {
            $rate = 0.20;
        } elseif ($price > 1000) {
            $rate = 0.10;
        } elseif ($price <= 250) {
            $rate = 0.05;
        } else {
            $rate = 0.05; // Faixa Intermediária, assumida como sendo 5%
        }

        return round($price * $rate, 2);
    }

    // Finalizar o Serviço
    public function finish(int $id): ?array
    {
        $service = $this->findById($id);

        if (!$service || $service['finished_at'] !== null) {
            return null;
        }

        $commission = $this->calculateCommission((float) $service['price']);

        $stmt = Database::getInstance()->prepare(
            'UPDATE service SET finished_at = NOW(), commission_user = :commission WHERE id_service = :id'
        );
        $stmt->execute(['commission' => $commission, 'id' => $id]);

        $service['commission_user'] = $commission;
        $service['finished_at'] = date('Y-m-d H:i:s');
        $service['status'] = 'FINALIZADO';

        return $service;
    }
}