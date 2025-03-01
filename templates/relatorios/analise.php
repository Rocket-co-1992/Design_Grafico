<div class="analise-container">
    <div class="filtros-card card">
        <form id="formFiltros" class="filtros-form">
            <div class="filtros-grid">
                <div class="form-group">
                    <label>Período</label>
                    <div class="input-group">
                        <input type="date" name="data_inicio" class="form-control">
                        <span>até</span>
                        <input type="date" name="data_fim" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label>Setor</label>
                    <select name="setor" class="form-control">
                        <option value="todos">Todos</option>
                        <?php foreach ($setores as $setor): ?>
                            <option value="<?= $setor['id'] ?>"><?= htmlspecialchars($setor['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tipo Relatório</label>
                    <select name="tipo" class="form-control">
                        <option value="desempenho">Desempenho</option>
                        <option value="qualidade">Qualidade</option>
                        <option value="financeiro">Financeiro</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Gerar Análise</button>
            </div>
        </form>
    </div>

    <div class="analise-grid">
        <div class="metricas-principais card">
            <h3>Indicadores Chave</h3>
            <div class="metricas-grid">
                <?php foreach ($indicadores as $ind): ?>
                <div class="metrica-card">
                    <div class="metrica-header">
                        <span class="metrica-titulo"><?= htmlspecialchars($ind['nome']) ?></span>
                        <span class="metrica-info" data-tooltip="<?= htmlspecialchars($ind['descricao']) ?>">
                            <i class="icon-info"></i>
                        </span>
                    </div>
                    <div class="metrica-valor <?= $ind['status'] ?>">
                        <?= $ind['valor'] ?><?= $ind['unidade'] ?>
                    </div>
                    <div class="metrica-comparativo">
                        <span class="variacao <?= $ind['variacao'] >= 0 ? 'positiva' : 'negativa' ?>">
                            <?= $ind['variacao'] ?>%
                        </span>
                        vs. período anterior
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="graficos-container">
            <div class="card grafico-card">
                <div class="grafico-header">
                    <h3>Análise Temporal</h3>
                    <div class="grafico-controles">
                        <select class="form-control" onchange="atualizarGrafico(this.value)">
                            <option value="diario">Diário</option>
                            <option value="semanal">Semanal</option>
                            <option value="mensal">Mensal</option>
                        </select>
                        <button class="btn btn-outline" onclick="exportarGrafico()">
                            <i class="icon-download"></i>
                        </button>
                    </div>
                </div>
                <div class="grafico-area">
                    <canvas id="graficoTemporal"></canvas>
                </div>
            </div>

            <div class="card tabela-analise">
                <h3>Detalhamento</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Período</th>
                                <?php foreach ($colunas as $coluna): ?>
                                    <th><?= htmlspecialchars($coluna) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dados_tabela as $linha): ?>
                            <tr>
                                <td><?= $linha['periodo'] ?></td>
                                <?php foreach ($colunas as $coluna): ?>
                                    <td class="<?= $linha[$coluna.'_class'] ?? '' ?>">
                                        <?= $linha[$coluna] ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
