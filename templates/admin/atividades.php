<div class="log-container">
    <div class="page-header">
        <h2>Log de Atividades</h2>
        <div class="header-actions">
            <button class="btn btn-outline-secondary" id="exportarLog">Exportar Log</button>
            <select class="form-control" id="filtroTipo">
                <option value="">Todos os tipos</option>
                <option value="login">Login</option>
                <option value="pedido">Pedidos</option>
                <option value="producao">Produção</option>
                <option value="config">Configurações</option>
            </select>
        </div>
    </div>

    <div class="log-timeline">
        <?php foreach ($atividades as $data => $logs): ?>
        <div class="timeline-day">
            <div class="day-header"><?= date('d/m/Y', strtotime($data)) ?></div>
            
            <?php foreach ($logs as $log): ?>
            <div class="log-entry">
                <div class="log-time"><?= date('H:i', strtotime($log['created_at'])) ?></div>
                <div class="log-icon log-<?= $log['tipo'] ?>"></div>
                <div class="log-content">
                    <div class="log-title">
                        <?= htmlspecialchars($log['usuario_nome']) ?> 
                        <span class="log-action"><?= htmlspecialchars($log['acao']) ?></span>
                    </div>
                    <div class="log-details">
                        <?= htmlspecialchars($log['detalhes']) ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if ($totalPaginas > 1): ?>
    <div class="log-pagination">
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="?pagina=<?= $i ?>" class="page-link <?= $paginaAtual == $i ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>
