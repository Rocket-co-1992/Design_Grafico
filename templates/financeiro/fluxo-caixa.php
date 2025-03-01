<div class="fluxo-container">
    <div class="page-header">
        <h2>Fluxo de Caixa</h2>
        <div class="header-actions">
            <select class="form-control" id="periodoFluxo">
                <option value="7">Últimos 7 dias</option>
                <option value="15">Últimos 15 dias</option>
                <option value="30" selected>Últimos 30 dias</option>
            </select>
            <button class="btn btn-outline" onclick="exportarFluxo()">Exportar</button>
        </div>
    </div>

    <div class="saldo-cards">
        <div class="saldo-card entrada">
            <h3>Entradas</h3>
            <div class="valor">R$ <?= number_format($saldos['entradas'], 2, ',', '.') ?></div>
            <div class="comparativo">
                <?= ($saldos['var_entradas'] >= 0 ? '+' : '') ?><?= $saldos['var_entradas'] ?>% em relação ao período anterior
            </div>
        </div>

        <div class="saldo-card saida">
            <h3>Saídas</h3>
            <div class="valor">R$ <?= number_format($saldos['saidas'], 2, ',', '.') ?></div>
            <div class="comparativo">
                <?= ($saldos['var_saidas'] <= 0 ? '+' : '') ?><?= $saldos['var_saidas'] ?>% em relação ao período anterior
            </div>
        </div>

        <div class="saldo-card saldo">
            <h3>Saldo</h3>
            <div class="valor <?= $saldos['saldo'] >= 0 ? 'positivo' : 'negativo' ?>">
                R$ <?= number_format(abs($saldos['saldo']), 2, ',', '.') ?>
            </div>
        </div>
    </div>

    <div class="fluxo-grid">
        <div class="card grafico-fluxo">
            <canvas id="fluxoChart"></canvas>
        </div>

        <div class="card movimentacoes">
            <h3>Últimas Movimentações</h3>
            <div class="movimentacoes-lista">
                <?php foreach ($movimentacoes as $mov): ?>
                <div class="movimentacao-item tipo-<?= $mov['tipo'] ?>">
                    <div class="mov-data"><?= date('d/m/Y', strtotime($mov['data'])) ?></div>
                    <div class="mov-descricao"><?= htmlspecialchars($mov['descricao']) ?></div>
                    <div class="mov-valor">R$ <?= number_format($mov['valor'], 2, ',', '.') ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
