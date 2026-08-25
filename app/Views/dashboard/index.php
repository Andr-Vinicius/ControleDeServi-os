<div class="dashboard-header">
    <h1>DASHBOARD</h1>
    <p class="today"><?= htmlspecialchars($today ?? date('d/m/Y')) ?></p>
</div>

<?php if (!empty($message)): ?>
    <p class="alert alert-<?= $message['type'] === 'success' ? 'success' : 'error' ?>">
        <?= htmlspecialchars($message['text']) ?>
    </p>
<?php endif; ?>

<div class="highlight-box">
    <strong>Total prestado por você:</strong>
    R$ <?= number_format($totalValue ?? 0, 2, ',', '.') ?>
</div>

<div class="dashboard-lists">
    <div class="dashboard-list-col">
        <h2>Ultimos Serviços</h2>
        <ul class="pending-list">
            <?php foreach ($latestServices ?? [] as $l): ?>
                <li>#<?= (int) $l['id_service'] ?> - <?= htmlspecialchars($l['description']) ?></li>
            <?php endforeach; ?>
            <?php if (empty($latestServices)): ?>
                <li class="empty">Nenhum serviço cadastrado.</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="dashboard-list-col">
        <h2>Serviços Pendentes</h2>
        <ul class="pending-list">
            <?php foreach ($pendingServices ?? [] as $p): ?>
                <li>#<?= (int) $p['id_service'] ?> - <?= htmlspecialchars($p['description']) ?></li>
            <?php endforeach; ?>
            <?php if (empty($pendingServices)): ?>
                <li class="empty">Nenhum serviço pendente.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<div class="toolbar">
    <a href="<?= BASE_URL ?>/service/create" class="btn btn-primary">+ Novo Serviço</a>
</div>

<form method="GET" action="<?= BASE_URL ?>/dashboard" class="filter-bar">
    <label class="filter-label">Descrição
        <input type="text" name="descricao" placeholder="Nome do serviço"
               value="<?= htmlspecialchars($filters['descricao'] ?? '') ?>">
    </label>
    <label class="filter-label">Usuário
        <input type="text" name="usuario" placeholder="Nome do usuário"
               value="<?= htmlspecialchars($filters['usuario'] ?? '') ?>">
    </label>
    <label class="filter-label">Status
        <select name="status">
            <option value="">Todos</option>
            <option value="PENDENTE" <?= ($filters['status'] ?? '') === 'PENDENTE' ? 'selected' : '' ?>>Pendente</option>
            <option value="FINALIZADO" <?= ($filters['status'] ?? '') === 'FINALIZADO' ? 'selected' : '' ?>>Finalizado</option>
        </select>
    </label>
    <label class="date-label">De
        <input type="date" name="data_inicio" value="<?= htmlspecialchars($filters['data_inicio'] ?? '') ?>">
    </label>
    <label class="date-label">Até
        <input type="date" name="data_fim" value="<?= htmlspecialchars($filters['data_fim'] ?? '') ?>">
    </label>
    <button type="submit" class="btn btn-filter">Filtrar</button>
    <a href="<?= BASE_URL ?>/dashboard" class="btn btn-secondary">Limpar</a>
</form>

<table class="services-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Usuário</th>
            <th>Valor</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($services ?? [] as $s): ?>
            <tr>
                <td>#<?= (int) $s['id_service'] ?></td>
                <td><?= htmlspecialchars($s['description']) ?></td>
                <td><?= htmlspecialchars($s['user_name']) ?></td>
                <td>R$ <?= number_format($s['price'], 2, ',', '.') ?></td>
                <td>
                    <span class="badge badge-<?= strtolower($s['status']) ?>"><?= $s['status'] ?></span>
                </td>
                <td class="actions-cell">
                    <a href="<?= BASE_URL ?>/service/edit?id=<?= (int) $s['id_service'] ?>"
                       class="btn btn-small btn-edit">Alterar</a>

                    <form method="POST" action="<?= BASE_URL ?>/service/delete" class="inline-form"
                          onsubmit="return confirm('Deseja realmente excluir este serviço?');">
                        <input type="hidden" name="id" value="<?= (int) $s['id_service'] ?>">
                        <button type="submit" class="btn btn-small btn-danger">Excluir</button>
                    </form>

                    <?php if ($s['status'] === 'PENDENTE'): ?>
                        <form method="POST" action="<?= BASE_URL ?>/service/finish" class="inline-form"
                              onsubmit="return confirm('Finalizar este serviço? Isso irá calcular a comissão e enviar um email ao usuário.');">
                            <input type="hidden" name="id" value="<?= (int) $s['id_service'] ?>">
                            <button type="submit" class="btn btn-small btn-success">Finalizar</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (empty($services)): ?>
            <tr>
                <td colspan="6" class="empty">Nenhum serviço encontrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>