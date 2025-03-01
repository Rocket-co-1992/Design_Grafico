<div class="maintenance-form">
    <div class="page-header">
        <h2>Registro de Manutenção</h2>
        <span class="equipment-name">
            <?= htmlspecialchars($equipamento['nome']) ?> (<?= htmlspecialchars($equipamento['modelo']) ?>)
        </span>
    </div>

    <form id="formManutencao" method="POST" action="/equipamentos/salvar-manutencao">
        <input type="hidden" name="equipamento_id" value="<?= $equipamento['id'] ?>">
        
        <div class="form-grid">
            <div class="card">
                <h3>Dados da Manutenção</h3>
                <div class="form-row">
                    <div class="form-group col-6">
                        <label>Tipo de Manutenção</label>
                        <select name="tipo" class="form-control" required>
                            <option value="preventiva">Preventiva</option>
                            <option value="corretiva">Corretiva</option>
                            <option value="calibragem">Calibragem</option>
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label>Data Realização</label>
                        <input type="date" name="data_realizada" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Descrição do Serviço</label>
                    <textarea name="descricao" class="form-control" rows="4" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-6">
                        <label>Custo (R$)</label>
                        <input type="number" name="custo" step="0.01" class="form-control">
                    </div>
                    <div class="form-group col-6">
                        <label>Próxima Manutenção</label>
                        <input type="date" name="proxima_manutencao" class="form-control">
                    </div>
                </div>
            </div>

            <div class="card">
                <h3>Checklist de Verificação</h3>
                <div class="checklist-items">
                    <?php foreach ($checklist as $item): ?>
                    <div class="checklist-item">
                        <label class="checkbox-container">
                            <input type="checkbox" name="checklist[]" value="<?= $item['id'] ?>">
                            <span class="checkmark"></span>
                            <?= htmlspecialchars($item['descricao']) ?>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Registrar Manutenção</button>
            <button type="button" class="btn btn-outline" onclick="history.back()">Cancelar</button>
        </div>
    </form>
</div>
