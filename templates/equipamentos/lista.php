<div class="equipment-dashboard">
    <div class="page-header">
        <h2>Gestão de Equipamentos</h2>
        <div class="page-actions">
            <button class="btn btn-primary" onclick="novoEquipamento()">Novo Equipamento</button>
            <button class="btn btn-outline" onclick="gerarRelatorio()">Relatório</button>
        </div>
    </div>

    <div class="status-overview">
        <div class="status-card operacional">
            <span class="count"><?= $stats['operacional'] ?></span>
            <span class="label">Operacional</span>
        </div>
        <div class="status-card manutencao">
            <span class="count"><?= $stats['manutencao'] ?></span>
            <span class="label">Em Manutenção</span>
        </div>
        <div class="status-card atencao">
            <span class="count"><?= $stats['atencao'] ?></span>
            <span class="label">Necessita Atenção</span>
        </div>
    </div>

    <div class="equipment-list">
        <?php foreach ($equipamentos as $equip): ?>
        <div class="equipment-card status-<?= $equip['status'] ?>">
            <div class="equipment-header">
                <h3><?= htmlspecialchars($equip['nome']) ?></h3>
                <span class="model"><?= htmlspecialchars($equip['modelo']) ?></span>
            </div>
            
            <div class="stats-grid">
                <div class="stat">
                    <label>Última Manutenção</label>
                    <span><?= date('d/m/Y', strtotime($equip['ultima_manutencao'])) ?></span>
                </div>
                <div class="stat">
                    <label>Próxima Prevista</label>
                    <span><?= date('d/m/Y', strtotime($equip['proxima_manutencao'])) ?></span>
                </div>
                <div class="stat">
                    <label>Total Impressões</label>
                    <span><?= number_format($equip['contador_impressoes']) ?></span>
                </div>
            </div>

            <div class="equipment-footer">
                <button class="btn btn-sm" onclick="registrarManutencao(<?= $equip['id'] ?>)">
                    Registrar Manutenção
                </button>
                <button class="btn btn-sm btn-outline" onclick="verHistorico(<?= $equip['id'] ?>)">
                    Histórico
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
