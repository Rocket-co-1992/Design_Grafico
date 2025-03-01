<div class="equipamentos-status">
    <div class="status-overview">
        <div class="card status-total">
            <div class="status-header">
                <i class="icon-printer"></i>
                <span>Total de Equipamentos</span>
            </div>
            <div class="status-value"><?= $totais['total'] ?></div>
            <div class="status-info">
                <span class="operacionais"><?= $totais['operacionais'] ?> operacionais</span>
                <span class="manutencao"><?= $totais['manutencao'] ?> em manutenção</span>
            </div>
        </div>

        <div class="card status-producao">
            <h3>Produção Atual</h3>
            <div class="producao-dados">
                <div class="dado">
                    <span class="label">Velocidade Média</span>
                    <span class="valor"><?= $producao['velocidade_media'] ?> imp/h</span>
                </div>
                <div class="dado">
                    <span class="label">Eficiência</span>
                    <span class="valor"><?= number_format($producao['eficiencia'], 1) ?>%</span>
                </div>
            </div>
        </div>

        <div class="card status-manutencoes">
            <h3>Próximas Manutenções</h3>
            <div class="manutencoes-list">
                <?php foreach ($proximas_manutencoes as $manutencao): ?>
                <div class="manutencao-item">
                    <div class="equipamento-info">
                        <span class="nome"><?= htmlspecialchars($manutencao['equipamento']) ?></span>
                        <span class="tipo"><?= htmlspecialchars($manutencao['tipo']) ?></span>
                    </div>
                    <div class="prazo <?= strtotime($manutencao['data']) < time() ? 'atrasado' : '' ?>">
                        <?= date('d/m/Y', strtotime($manutencao['data'])) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="equipamentos-grid">
        <?php foreach ($equipamentos as $equip): ?>
        <div class="equipamento-card card">
            <div class="equipamento-header">
                <div class="equipamento-nome">
                    <h3><?= htmlspecialchars($equip['nome']) ?></h3>
                    <span class="modelo"><?= htmlspecialchars($equip['modelo']) ?></span>
                </div>
                <span class="status-badge status-<?= $equip['status'] ?>">
                    <?= ucfirst($equip['status']) ?>
                </span>
            </div>

            <div class="equipamento-metricas">
                <div class="metrica">
                    <span class="label">Contador</span>
                    <span class="valor"><?= number_format($equip['contador']) ?></span>
                </div>
                <div class="metrica">
                    <span class="label">Última Manutenção</span>
                    <span class="valor"><?= date('d/m/Y', strtotime($equip['ultima_manutencao'])) ?></span>
                </div>
                <div class="metrica">
                    <span class="label">Disponibilidade</span>
                    <span class="valor"><?= number_format($equip['disponibilidade'], 1) ?>%</span>
                </div>
            </div>

            <div class="equipamento-grafico">
                <canvas id="grafico-<?= $equip['id'] ?>" height="100"></canvas>
            </div>

            <div class="equipamento-acoes">
                <button class="btn btn-sm" onclick="verHistorico(<?= $equip['id'] ?>)">
                    Histórico
                </button>
                <button class="btn btn-sm" onclick="registrarManutencao(<?= $equip['id'] ?>)">
                    Manutenção
                </button>
                <button class="btn btn-sm btn-outline" onclick="editarEquipamento(<?= $equip['id'] ?>)">
                    Configurar
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
