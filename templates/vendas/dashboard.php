<div class="vendas-dashboard">
    <div class="vendas-header">
        <h2>Dashboard de Vendas</h2>
        <div class="periodo-selector">
            <select class="form-control" id="periodoVendas">
                <option value="hoje">Hoje</option>
                <option value="semana">Esta Semana</option>
                <option value="mes" selected>Este Mês</option>
            </select>
        </div>
    </div>

    <div class="metricas-grid">
        <div class="metrica-card vendas">
            <div class="metrica-icon"><i class="icon-cart"></i></div>
            <div class="metrica-info">
                <span class="valor">R$ <?= number_format($metricas['total_vendas'], 2, ',', '.') ?></span>
                <span class="label">Total em Vendas</span>
            </div>
            <div class="variacao <?= $metricas['variacao_vendas'] >= 0 ? 'positiva' : 'negativa' ?>">
                <?= $metricas['variacao_vendas'] ?>%
            </div>
        </div>
        
        <div class="metrica-card tickets">
            <div class="metrica-icon"><i class="icon-ticket"></i></div>
            <div class="metrica-info">
                <span class="valor"><?= $metricas['ticket_medio'] ?></span>
                <span class="label">Ticket Médio</span>
            </div>
        </div>

        <div class="metrica-card conversao">
            <div class="metrica-icon"><i class="icon-chart"></i></div>
            <div class="metrica-info">
                <span class="valor"><?= $metricas['taxa_conversao'] ?>%</span>
                <span class="label">Taxa de Conversão</span>
            </div>
        </div>
    </div>

    <div class="vendas-grid">
        <div class="card grafico-vendas">
            <h3>Evolução de Vendas</h3>
            <canvas id="chartVendas"></canvas>
        </div>

        <div class="card ranking-produtos">
            <h3>Produtos Mais Vendidos</h3>
            <div class="ranking-list">
                <?php foreach ($produtos_top as $produto): ?>
                <div class="ranking-item">
                    <span class="produto-nome"><?= htmlspecialchars($produto['nome']) ?></span>
                    <span class="produto-vendas"><?= $produto['quantidade'] ?> un.</span>
                    <div class="barra-progresso">
                        <div class="progresso" style="width: <?= $produto['percentual'] ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
