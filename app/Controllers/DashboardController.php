<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Service;

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $userId = (int) $_SESSION['user_id'];
        $serviceModel = new Service();

        $filters = [
            'descricao'   => trim($_GET['descricao'] ?? ''),
            'usuario'     => trim($_GET['usuario'] ?? ''),
            'status'      => trim($_GET['status'] ?? ''),
            'data_inicio' => trim($_GET['data_inicio'] ?? ''),
            'data_fim'    => trim($_GET['data_fim'] ?? ''),
        ];

        $message = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);

        $this->view('dashboard/index', [
            'services'        => $serviceModel->listFiltered($filters),
            'latestServices'  => $serviceModel->latestByUser($userId),
            'pendingServices' => $serviceModel->pendingByUser($userId),
            'totalValue'      => $serviceModel->totalByUser($userId),
            'today'           => date('d/m/Y'),
            'filters'         => $filters,
            'message'         => $message,
        ]);
    }
}