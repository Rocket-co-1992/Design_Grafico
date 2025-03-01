<div class="recompensas-container">
    <div class="page-header">
        <h2>Programa de Fidelidade</h2>
        <div class="pontos-disponiveis">
            <span class="label">Seus Pontos:</span>
            <span class="pontos"><?= number_format($pontos_cliente) ?></span>
        </div>
    </div>

    <div class="recompensas-grid">
        <?php foreach ($recompensas as $recompensa): ?>
        <div class="recompensa-card <?= $pontos_cliente >= $recompensa['pontos'] ? 'disponivel' : 'bloqueada' ?>">
            <div class="recompensa-header">
                <h3><?= htmlspecialchars($recompensa['nome']) ?></h3>
                <span class="pontos-necessarios"><?= number_format($recompensa['pontos']) ?> pontos</span>
            </div>
            <p class="descricao"><?= htmlspecialchars($recompensa['descricao']) ?></p>
            <div class="recompensa-footer">
                <?php if ($pontos_cliente >= $recompensa['pontos']): ?>
                    <button class="btn btn-primary" onclick="resgatarRecompensa(<?= $recompensa['id'] ?>)">
                        Resgatar
                    </button>
                <?php else: ?>
                    <div class="pontos-faltantes">
                        Faltam <?= number_format($recompensa['pontos'] - $pontos_cliente) ?> pontos
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="card historico-resgates">
        <h3>Histórico de Resgates</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Recompensa</th>
                        <th>Pontos</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historico as $resgate): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($resgate['data_resgate'])) ?></td>
                        <td><?= htmlspecialchars($resgate['recompensa_nome']) ?></td>
                        <td><?= number_format($resgate['pontos_usados']) ?></td>
                        <td><span class="status-badge status-<?= $resgate['status'] ?>"><?= $resgate['status'] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
