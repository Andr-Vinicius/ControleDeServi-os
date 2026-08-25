<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Service;

class ServiceController extends Controller
{
    // Exibe o formulário de criação de serviço
    public function create(): void
    {
        $this->requireAuth();
        $this->view('service/create', []);
    }

    // Cadastro de Novo Serviço
    public function store(): void
    {
        $this->requireAuth();

        $description = trim($_POST['description'] ?? '');
        $price = $this->parsePrice($_POST['price'] ?? '');

        if ($description === '' || $price === null || $price <= 0) {
            $this->flash('error', 'Não foi possível cadastrar o serviço! Verifique a descrição e o valor informados.');
            $this->redirect('/dashboard');
            return;
        }

        (new Service())->create($description, $price, (int) $_SESSION['user_id']);

        $this->flash('success', 'Serviço cadastrado com sucesso!');
        $this->redirect('/dashboard');
    }

    // Tela de edição de serviço, puxando os dados correspondentes
    public function edit(): void
    {
        $this->requireAuth();

        $id = (int) ($_GET['id'] ?? 0);
        $service = (new Service())->findById($id);

        if (!$service) {
            $this->flash('error', 'Serviço não encontrado.');
            $this->redirect('/dashboard');
            return;
        }

        $this->view('service/edit', ['service' => $service]);
    }

    public function update(): void
    {
        $this->requireAuth();

        $id = (int) ($_POST['id'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $price = $this->parsePrice($_POST['price'] ?? '');

        if ($id <= 0 || $description === '' || $price === null || $price <= 0) {
            $this->flash('error', 'Não foi possível atualizar o serviço! Verifique os dados informados.');
            $this->redirect('/dashboard');
            return;
        }

        (new Service())->update($id, $description, $price);

        $this->flash('success', 'Serviço atualizado com sucesso!');
        $this->redirect('/dashboard');
    }

    public function delete(): void
    {
        $this->requireAuth();

        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            (new Service())->delete($id);
        }

        $this->flash('success', 'Serviço excluído com sucesso!');
        $this->redirect('/dashboard');
    }

    // Regra de Negócio - Finalizar Serviço
    public function finish(): void
    {
        $this->requireAuth();

        $id = (int) ($_POST['id'] ?? 0);
        $service = (new Service())->finish($id);

        if (!$service) {
            $this->flash('error', 'Não foi possível finalizar este serviço (verifique se ele já não está finalizado).');
            $this->redirect('/dashboard');
            return;
        }

        $this->sendFinishedEmail($service);

        $commissionFormatted = number_format($service['commission_user'], 2, ',', '.');
        $this->flash('success', "Serviço finalizado com sucesso! Comissão calculada: R$ {$commissionFormatted}");
        $this->redirect('/dashboard');
    }

    private function parsePrice(string $raw): ?float
    {
        $raw = trim($raw);
        $raw = str_replace('.', '', $raw); // remove separador de milhar caso houver
        $raw = str_replace(',', '.', $raw); // vírgula -> ponto 

        return is_numeric($raw) ? (float) $raw : null;
    }

    // Email de notificação de finalização do serviço para o usuário responsável.
    private function sendFinishedEmail(array $service): void
    {
        if (empty($service['user_email'])) {
            return;
        }

        $to = $service['user_email'];
        $subject = 'Serviço #' . $service['id_service'] . ' finalizado';

        $body = "Olá {$service['user_name']},\n\n"
            . "O serviço \"{$service['description']}\" foi finalizado com sucesso.\n"
            . 'Valor do serviço: R$ ' . number_format((float) $service['price'], 2, ',', '.') . "\n"
            . 'Sua comissão: R$ ' . number_format((float) $service['commission_user'], 2, ',', '.') . "\n\n"
            . "Sistema de Controle de Serviços";

        $headers = 'From: no-reply@sistema-servicos.local' . "\r\n"
            . 'Content-Type: text/plain; charset=UTF-8' . "\r\n";

        try {
            // Função nativa mail() do PHP 
            @mail($to, $subject, $body, $headers);
        } catch (\Throwable $e) {
            // Falha de envio de email não deve quebrar o fluxo de finalização do serviço.
        }
    }
}
