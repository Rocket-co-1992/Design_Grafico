<div class="estoque-container">
    <div class="page-header">
        <h2>Controle de Estoque</h2>
        <div class="header-actions">
            <button class="btn btn-secondary" onclick="exportarRelatorio()">Relatório</button>
            <button class="btn btn-primary" onclick="novoMaterial()">Novo Material</button>
        </div>
    </div>

    <div class="estoque-dash">
        <div class="status-cards">
            <div class="card status-card">
                <span class="status-valor"><?= $stats['total_itens'] ?></span>
                <span class="status-label">Total de Itens</span>
            </div>
            <div class="card status-card alerta">
                <span class="status-valor"><?= $stats['abaixo_minimo'] ?></span>
                <span class="status-label">Abaixo do Mínimo</span>
            </div>
            <div class="card status-card critico">
                <span class="status-valor"><?= $stats['zerados'] ?></span>
                <span class="status-label">Em Falta</span>
            </div>
        </div>

        <div class="movimentacoes-grid">
            <div class="card materiais-tabela">
                <div class="table-header">
                    <div class="table-filtros">
                        <select class="form-control" onchange="filtrarCategoria(this.value)">
                            <option value="">Todas Categorias</option>
                            <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" class="form-control" placeholder="Buscar material..." 
                               onkeyup="buscarMaterial(this.value)">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Quantidade</th>
                                <th>Mínimo</th>
                                <th>Status</th>
                                <th>Última Entrada</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="materiaisLista">
                            <?php foreach ($materiais as $material): ?>
                            <tr class="<?= $material['quantidade'] <= $material['minimo'] ? 'alerta' : '' ?>">
                                <td>
                                    <div class="material-info">
                                        <span class="nome"><?= htmlspecialchars($material['nome']) ?></span>
                                        <span class="codigo">#<?= $material['codigo'] ?></span>
                                    </div>
                                </td>
                                <td><?= $material['quantidade'] . ' ' . $material['unidade'] ?></td>
                                <td><?= $material['minimo'] . ' ' . $material['unidade'] ?></td>
                                <td>
                                    <span class="status-badge status-<?= $material['status'] ?>">
                                        <?= ucfirst($material['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($material['ultima_entrada'])) ?></td>
                                <td class="acoes">
                                    <button class="btn btn-sm" onclick="ajustarEstoque(<?= $material['id'] ?>)">
                                        <i class="icon-edit"></i>
                                    </button>
                                    <button class="btn btn-sm" onclick="verHistorico(<?= $material['id'] ?>)">
                                        <i class="icon-history"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card ultimas-movimentacoes">
                <h3>Últimas Movimentações</h3>
                <div class="movimentacoes-lista">
                    <?php foreach ($movimentacoes as $mov): ?>
                    <div class="movimentacao-item tipo-<?= $mov['tipo'] ?>">
                        <div class="mov-icon">
                            <i class="icon-<?= $mov['tipo'] ?>"></i>
                        </div>
                        <div class="mov-info">
                            <div class="mov-principal">
                                <span class="material"><?= htmlspecialchars($mov['material_nome']) ?></span>
                                <span class="quantidade"><?= $mov['quantidade'] . ' ' . $mov['unidade'] ?></span>
                            </div>
                            <div class="mov-meta">
                                <span class="responsavel"><?= htmlspecialchars($mov['responsavel_nome']) ?></span>
                                <span class="data"><?= date('d/m/Y H:i', strtotime($mov['data'])) ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
