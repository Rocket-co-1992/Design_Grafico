<div class="precificacao-container">
    <div class="page-header">
        <h2>Gestão de Custos e Precificação</h2>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="atualizarIndices()">Atualizar Índices</button>
            <button class="btn btn-outline" onclick="exportarRelatorio()">Relatório</button>
        </div>
    </div>

    <div class="indices-grid">
        <div class="indice-card card">
            <h3>Custos Diretos</h3>
            <div class="custo-grupo">
                <div class="custo-item">
                    <span class="label">Matéria-prima</span>
                    <span class="valor">R$ <?= number_format($custos['materia_prima'], 2, ',', '.') ?></span>
                    <span class="variacao <?= $custos['variacao_mp'] >= 0 ? 'positiva' : 'negativa' ?>">
                        <?= $custos['variacao_mp'] ?>%
                    </span>
                </div>
                <div class="custo-item">
                    <span class="label">Mão de obra</span>
                    <span class="valor">R$ <?= number_format($custos['mao_obra'], 2, ',', '.') ?></span>
                    <span class="variacao <?= $custos['variacao_mo'] >= 0 ? 'positiva' : 'negativa' ?>">
                        <?= $custos['variacao_mo'] ?>%
                    </span>
                </div>
            </div>
            <div class="grafico-mini">
                <canvas id="graficoCustosDirects"></canvas>
            </div>
        </div>

        <div class="indice-card card">
            <h3>Custos Indiretos</h3>
            <div class="distribuicao-custos">
                <?php foreach ($custos_indiretos as $custo): ?>
                <div class="distribuicao-item">
                    <span class="nome"><?= htmlspecialchars($custo['nome']) ?></span>
                    <div class="barra-progresso">
                        <div class="progresso" style="width: <?= $custo['percentual'] ?>%"></div>
                    </div>
                    <span class="percentual"><?= number_format($custo['percentual'], 1) ?>%</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="indice-card card">
            <h3>Margens e Preços</h3>
            <div class="margem-grupo">
                <?php foreach ($margens as $categoria => $margem): ?>
                <div class="margem-item">
                    <div class="margem-info">
                        <span class="categoria"><?= htmlspecialchars($categoria) ?></span>
                        <span class="percentual"><?= number_format($margem['percentual'], 1) ?>%</span>
                    </div>
                    <input type="range" 
                           class="margem-slider" 
                           min="0" 
                           max="100" 
                           value="<?= $margem['percentual'] ?>"
                           onchange="atualizarMargem('<?= $categoria ?>', this.value)">
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="simulador card">
        <h3>Simulador de Preços</h3>
        <div class="simulador-grid">
            <div class="parametros-form">
                <div class="form-group">
                    <label>Produto Base</label>
                    <select class="form-control" onchange="carregarParametros(this.value)">
                        <?php foreach ($produtos as $produto): ?>
                        <option value="<?= $produto['id'] ?>"><?= htmlspecialchars($produto['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="parametrosDinamicos">
                    <!-- Preenchido via JavaScript -->
                </div>
            </div>

            <div class="resultado-simulacao">
                <div class="breakdown">
                    <div class="breakdown-item">
                        <span>Custo Base:</span>
                        <span id="custoBase">R$ 0,00</span>
                    </div>
                    <div class="breakdown-item">
                        <span>Custos Indiretos:</span>
                        <span id="custosIndiretos">R$ 0,00</span>
                    </div>
                    <div class="breakdown-item">
                        <span>Margem:</span>
                        <span id="margemValor">R$ 0,00</span>
                    </div>
                    <div class="breakdown-total">
                        <span>Preço Final:</span>
                        <span id="precoFinal">R$ 0,00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
