<div class="ordem-container">
    <header class="ordem-header">
        <div class="ordem-titulo">
            <h2>Ordem de Serviço #<?= $ordem['id'] ?></h2>
            <span class="status status-<?= $ordem['status'] ?>"><?= ucfirst($ordem['status']) ?></span>
        </div>
        <div class="ordem-acoes">
            <button class="btn btn-outline" onclick="imprimirOS(<?= $ordem['id'] ?>)">
                <i class="icon-print"></i> Imprimir
            </button>
            <button class="btn btn-primary" onclick="atualizarStatus(<?= $ordem['id'] ?>)">
                Atualizar Status
            </button>
        </div>
    </header>

    <div class="ordem-grid">
        <div class="card info-pedido">
            <h3>Informações do Pedido</h3>
            <div class="info-grupo">
                <div class="info-item">
                    <label>Cliente:</label>
                    <span><?= htmlspecialchars($ordem['cliente_nome']) ?></span>
                </div>
                <div class="info-item">
                    <label>Prazo de Entrega:</label>
                    <span class="<?= strtotime($ordem['prazo']) < time() ? 'atrasado' : '' ?>">
                        <?= date('d/m/Y', strtotime($ordem['prazo'])) ?>
                    </span>
                </div>
                <div class="info-item">
                    <label>Prioridade:</label>
                    <span class="prioridade prioridade-<?= $ordem['prioridade'] ?>">
                        <?= ucfirst($ordem['prioridade']) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="card timeline-producao">
            <h3>Timeline de Produção</h3>
            <div class="timeline">
                <?php foreach ($etapas as $etapa): ?>
                <div class="etapa-item <?= $etapa['concluida'] ? 'concluida' : '' ?>">
                    <div class="etapa-status">
                        <div class="status-marker"></div>
                        <div class="status-line"></div>
                    </div>
                    <div class="etapa-conteudo">
                        <div class="etapa-header">
                            <h4><?= htmlspecialchars($etapa['nome']) ?></h4>
                            <span class="etapa-tempo"><?= $etapa['tempo_estimado'] ?> min</span>
                        </div>
                        <div class="etapa-detalhes">
                            <span class="responsavel">
                                <?= htmlspecialchars($etapa['responsavel_nome']) ?>
                            </span>
                            <?php if ($etapa['observacoes']): ?>
                                <p class="observacoes"><?= htmlspecialchars($etapa['observacoes']) ?></p>
                            <?php endif; ?>
                        </div>
                        <?php if (!$etapa['concluida']): ?>
                            <button class="btn btn-sm" onclick="concluirEtapa(<?= $etapa['id'] ?>)">
                                Concluir Etapa
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card controle-qualidade">
            <h3>Controle de Qualidade</h3>
            <div class="checklist">
                <?php foreach ($checklist as $item): ?>
                <div class="checklist-item">
                    <label class="checkbox-container">
                        <input type="checkbox" 
                               <?= $item['verificado'] ? 'checked' : '' ?>
                               onchange="verificarItem(<?= $item['id'] ?>)">
                        <span class="checkmark"></span>
                        <?= htmlspecialchars($item['descricao']) ?>
                    </label>
                    <?php if ($item['critico']): ?>
                        <span class="badge badge-danger">Crítico</span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
