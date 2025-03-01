<div class="qualidade-container">
    <div class="qualidade-header">
        <h2>Controle de Qualidade - OP #<?= $ordem['id'] ?></h2>
        <div class="status-info">
            <span class="status status-<?= $ordem['status'] ?>"><?= ucfirst($ordem['status']) ?></span>
        </div>
    </div>

    <form class="checklist-form" method="POST" action="/qualidade/salvar/<?= $inspecao['id'] ?>">
        <div class="produtos-info card">
            <h3>Informações do Produto</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Produto:</label>
                    <span><?= htmlspecialchars($ordem['produto_nome']) ?></span>
                </div>
                <div class="info-item">
                    <label>Quantidade:</label>
                    <span><?= $ordem['quantidade'] ?> unidades</span>
                </div>
                <div class="info-item">
                    <label>Material:</label>
                    <span><?= htmlspecialchars($ordem['material']) ?></span>
                </div>
                <div class="info-item">
                    <label>Acabamento:</label>
                    <span><?= htmlspecialchars($ordem['acabamento']) ?></span>
                </div>
            </div>
        </div>

        <div class="checklist card">
            <h3>Checklist de Verificação</h3>
            <?php foreach ($itens_checklist as $item): ?>
            <div class="checklist-item">
                <div class="item-header">
                    <label class="checkbox-container">
                        <input type="checkbox" name="items[<?= $item['id'] ?>][conforme]" value="1" 
                               <?= isset($item['resultado']) && $item['resultado']['conforme'] ? 'checked' : '' ?>>
                        <span class="checkmark"></span>
                        <?= htmlspecialchars($item['descricao']) ?>
                    </label>
                </div>
                <div class="item-obs">
                    <textarea name="items[<?= $item['id'] ?>][observacao]" placeholder="Observações..."
                              class="form-control"><?= $item['resultado']['observacao'] ?? '' ?></textarea>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar Inspeção</button>
            <button type="button" class="btn btn-danger" id="reportarProblema">Reportar Problema</button>
        </div>
    </form>
</div>
