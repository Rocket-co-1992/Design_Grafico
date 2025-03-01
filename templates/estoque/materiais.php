<div class="estoque-container">
    <div class="page-header">
        <h2>Controle de Materiais</h2>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="novoMaterial()">Novo Material</button>
            <button class="btn btn-outline" onclick="gerarRelatorio()">Relatório</button>
        </div>
    </div>

    <div class="materiais-grid">
        <div class="card filtros-card">
            <h3>Filtros</h3>
            <form class="filtros-form">
                <div class="form-group">
                    <label>Categoria</label>
                    <select class="form-control" name="categoria">
                        <option value="">Todas</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <div class="checkbox-group">
                        <label class="checkbox-container">
                            <input type="checkbox" name="status[]" value="disponivel" checked>
                            <span class="checkmark"></span>
                            Disponível
                        </label>
                        <label class="checkbox-container">
                            <input type="checkbox" name="status[]" value="baixo">
                            <span class="checkmark"></span>
                            Estoque Baixo
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-secondary">Aplicar Filtros</button>
            </form>
        </div>

        <div class="materiais-lista card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Material</th>
                            <th>Quantidade</th>
                            <th>Mínimo</th>
                            <th>Status</th>
                            <th>Última Compra</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($materiais as $material): ?>
                        <tr class="<?= $material['quantidade'] <= $material['minimo'] ? 'alerta' : '' ?>">
                            <td><?= htmlspecialchars($material['nome']) ?></td>
                            <td><?= $material['quantidade'] . ' ' . $material['unidade'] ?></td>
                            <td><?= $material['minimo'] . ' ' . $material['unidade'] ?></td>
                            <td>
                                <span class="status-badge status-<?= $material['status'] ?>">
                                    <?= ucfirst($material['status']) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($material['ultima_compra'])) ?></td>
                            <td class="acoes">
                                <button class="btn-icon" onclick="ajustarEstoque(<?= $material['id'] ?>)">
                                    <i class="icon-edit"></i>
                                </button>
                                <button class="btn-icon" onclick="verHistorico(<?= $material['id'] ?>)">
                                    <i class="icon-history"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
