<div class="financeiro-dashboard">
    <div class="metricas-grid">
        <div class="metrica-card">
            <h3>Receitas do Mês</h3>
            <div class="valor receita">
                <span class="cifrao">R$</span>
                <span class="numero"><?= number_format($metricas['receitas'], 2, ',', '.') ?></span>
            </div>
            <div class="variacao <?= $metricas['variacao_receitas'] >= 0 ? 'positiva' : 'negativa' ?>">
                <?= $metricas['variacao_receitas'] ?>% em relação ao mês anterior
            </div>
        </div>

        <div class="metrica-card">
            <h3>Despesas do Mês</h3>
            <div class="valor despesa">
                <span class="cifrao">R$</span>
                <span class="numero"><?= number_format($metricas['despesas'], 2, ',', '.') ?></span>
            </div>
            <div class="variacao <?= $metricas['variacao_despesas'] <= 0 ? 'positiva' : 'negativa' ?>">
                <?= $metricas['variacao_despesas'] ?>% em relação ao mês anterior
            </div>
        </div>

        <div class="metrica-card">
            <h3>Inadimplência</h3>
            <div class="valor alerta">
                <span class="cifrao">R$</span>
                <span class="numero"><?= number_format($metricas['inadimplencia'], 2, ',', '.') ?></span>
            </div>
            <div class="taxa">Taxa: <?= number_format($metricas['taxa_inadimplencia'], 1) ?>%</div>
        </div>
    </div>

    <div class="fluxo-caixa card">
        <div class="card-header">
            <h3>Fluxo de Caixa</h3>
            <div class="filtros">
                <select class="form-control" id="periodo">
                    <option value="7">Últimos 7 dias</option>
                    <option value="15">Últimos 15 dias</option>
                    <option value="30" selected>Últimos 30 dias</option>
                </select>
                <button class="btn btn-outline" onclick="exportarFluxo()">Exportar</button>
            </div>
        </div>
        <div class="grafico-container">
            <canvas id="graficoFluxo"></canvas>
        </div>
    </div>
</div>
