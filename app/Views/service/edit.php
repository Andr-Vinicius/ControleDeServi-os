<h1>Alterar Serviço</h1>

<?php if (isset($service)): ?>
<form method="POST" action="<?= BASE_URL ?>/service/update" class="form-box">
    <input type="hidden" name="id" value="<?= (int) $service['id_service'] ?>">
    <input type="text" name="description" placeholder="descrição" required maxlength="255"
           value="<?= htmlspecialchars($service['description']) ?>">
    <input type="text" name="price" placeholder="preço" required inputmode="decimal"
           value="<?= htmlspecialchars((string) $service['price']) ?>">

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
<?php else: ?>
    <p>Serviço não encontrado.</p>
<?php endif; ?>
