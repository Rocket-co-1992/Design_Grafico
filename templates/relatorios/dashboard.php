<div class="relatorios-dashboard">
    <div class="dashboard-header">
        <h2>Dashboard de Análises</h2>
        <div class="periodo-selector">
            <select class="form-control" id="periodoDashboard">
                <option value="7">Últimos 7 dias</option>
                <option value="30" selected>Últimos 30 dias</option>
                <option value="90">Últimos 90 dias</option>
                <option value="custom">Período personalizado</option>
            </select>
            <div class="datas-custom" style="display: none;">
                <input type="date" id="dataInicio" class="form-control">
                <input type="date" id="dataFim" class="form-control">
            </div>
        </div>
    </div>

    <div class="metricas-grid">
        <div class="metrica-card">
            <h3>Desempenho Produtivo</h3>
            <div class="metrica-valor">
                <span class="numero"><?= number_format($metricas['eficiencia'], 1) ?>%</span>
                <span class="label">Eficiência Operacional</span>
            </div>
            <div class="mini-chart" id="chartEficiencia"></div>
        </div>

        <div class="metrica-card">
            <h3>Qualidade</h3>
            <div class="qualidade-indices">
                <div class="indice">
                    <span class="valor"><?= number_format($metricas['conformidade'], 1) ?>%</span>
                    <span class="label">Conformidade</span>
                </div>
                <div class="indice">
                    <span class="valor"><?= $metricas['retrabalhos'] ?></span>
                    <span class="label">Retrabalhos</span>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress" style="width: <?= $metricas['conformidade'] ?>%"></div>
            </div>
        </div>

        <div class="metrica-card">
            <h3>Custos</h3>
            <div class="custos-breakdown">
                <div class="custo-item">
                    <span class="label">Matéria-prima</span>
                    <span class="valor">R$ <?= number_format($metricas['custo_mp'], 2, ',', '.') ?></span>
                </div>
                <div class="custo-item">
                    <span class="label">Operacional</span>
                    <span class="valor">R$ <?= number_format($metricas['custo_op'], 2, ',', '.') ?></span>
                </div>
                <div class="custo-item total">
                    <span class="label">Total</span>
                    <span class="valor">R$ <?= number_format($metricas['custo_total'], 2, ',', '.') ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="graficos-grid">
        <div class="grafico-card">
            <div class="card-header">
                <h3>Produção por Categoria</h3>
                <div class="chart-actions">
                    <button class="btn btn-sm" onclick="exportarDados('producao')">
                        <i class="icon-download"></i>
                    </button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="chartProducao"></canvas>
            </div>
        </div>

        <div class="grafico-card">
            <div class="card-header">
                <h3>Análise de Tempos</h3>
                <div class="chart-actions">
                    <button class="btn btn-sm" onclick="exportarDados('tempos')">
                        <i class="icon-download"></i>
                    </button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="chartTempos"></canvas>
            </div>
        </div>
    </div>

    <div class="destaques-grid">
        <div class="card top-produtos">
            <h3>Top Produtos</h3>
            <div class="lista-ranking">
                <?php foreach ($top_produtos as $produto): ?>
                <div class="ranking-item">
                    <div class="item-info">
                        <span class="nome"><?= htmlspecialchars($produto['nome']) ?></span>
                        <span class="quantidade"><?= $produto['quantidade'] ?> un.</span>
                    </div>
                    <div class="item-barra">
                        <div class="barra-progresso" style="width: <?= $produto['percentual'] ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card alertas">
            <h3>Alertas e Ações</h3>
            <div class="lista-alertas">
                <?php foreach ($alertas as $alerta): ?>
                <div class="alerta-item prioridade-<?= $alerta['prioridade'] ?>">
                    <div class="alerta-icon">
                        <i class="icon-<?= $alerta['tipo'] ?>"></i>
                    </div>
                    <div class="alerta-conteudo">
                        <div class="alerta-texto"><?= htmlspecialchars($alerta['mensagem']) ?></div>
                        <div class="alerta-meta">
                            <span class="data"><?= date('d/m/Y', strtotime($alerta['data'])) ?></span>
                            <?php if ($alerta['acao_necessaria']): ?>
                                <button class="btn btn-sm btn-outline" onclick="resolverAlerta(<?= $alerta['id'] ?>)">
                                    Resolver
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
