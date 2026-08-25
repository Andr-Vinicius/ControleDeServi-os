<h1>Cadastrar Novo Serviço</h1>

<form method="POST" action="<?= BASE_URL ?>/service/store" class="form-box">
    <input type="text" name="description" placeholder="Descrição" required maxlength="255">
    <input type="text" name="price" placeholder="Preço" required inputmode="decimal">

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Cadastrar</button>
        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-secondary">Cancelar</a>
    </div>
</form>
