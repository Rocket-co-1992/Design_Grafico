<div class="ordem-producao">
    <div class="ordem-header">
        <h2>Ordem de Produção #<?= $ordem['id'] ?></h2>
        <div class="ordem-status">
            <span class="status status-<?= $ordem['status'] ?>"><?= ucfirst($ordem['status']) ?></span>
        </div>
    </div>

    <div class="ordem-grid">
        <div class="card ordem-detalhes">
            <h3>Detalhes da Produção</h3>
            <div class="detalhes-grid">
                <div class="detalhe-item">
                    <label>Início Previsto:</label>
                    <span><?= date('d/m/Y', strtotime($ordem['data_inicio_prevista'])) ?></span>
                </div>
                <div class="detalhe-item">
                    <label>Prazo Final:</label>
                    <span><?= date('d/m/Y', strtotime($ordem['data_fim_prevista'])) ?></span>
                </div>
                <div class="detalhe-item">
                    <label>Responsável:</label>
                    <span><?= htmlspecialchars($ordem['responsavel_nome']) ?></span>
                </div>
                <div class="detalhe-item">
                    <label>Prioridade:</label>
                    <span class="prioridade prioridade-<?= $ordem['prioridade'] ?>">
                        <?= ucfirst($ordem['prioridade']) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="card ordem-etapas">
            <h3>Etapas de Produção</h3>
            <div class="etapas-timeline">
                <?php foreach ($etapas as $etapa): ?>
                <div class="etapa-item">
                    <div class="etapa-status">
                        <div class="status-marker <?= $etapa['concluida'] ? 'concluida' : '' ?>"></div>
                    </div>
                    <div class="etapa-conteudo">
                        <h4><?= htmlspecialchars($etapa['nome']) ?></h4>
                        <div class="etapa-info">
                            <span>Responsável: <?= htmlspecialchars($etapa['responsavel']) ?></span>
                            <span>Tempo Estimado: <?= $etapa['tempo_estimado'] ?>min</span>
                        </div>
                        <?php if ($etapa['observacoes']): ?>
                            <p class="etapa-obs"><?= htmlspecialchars($etapa['observacoes']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
