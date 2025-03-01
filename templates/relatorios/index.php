<div class="content-header">
    <h2>Relatórios</h2>
</div>

<div class="reports-grid">
    <div class="report-card" data-type="vendas">
        <h3>Relatório de Vendas</h3>
        <p>Análise detalhada das vendas por período</p>
        <form class="report-form">
            <div class="form-row">
                <div class="form-group">
                    <label>Período</label>
                    <select name="periodo" class="form-control">
                        <option value="hoje">Hoje</option>
                        <option value="semana">Última Semana</option>
                        <option value="mes">Último Mês</option>
                        <option value="personalizado">Personalizado</option>
                    </select>
                </div>
                <div class="form-group dates-custom" style="display:none;">
                    <label>De</label>
                    <input type="date" name="data_inicio" class="form-control">
                    <label>Até</label>
                    <input type="date" name="data_fim" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Gerar Relatório</button>
        </form>
    </div>

    <div class="report-card" data-type="producao">
        <h3>Relatório de Produção</h3>
        <p>Análise da produtividade e tempos de produção</p>
        <form class="report-form">
            <div class="form-group">
                <label>Tipo de Análise</label>
                <select name="tipo_analise" class="form-control">
                    <option value="produtividade">Produtividade</option>
                    <option value="tempos">Tempos de Produção</option>
                    <option value="falhas">Análise de Falhas</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Gerar Relatório</button>
        </form>
    </div>

    <div class="report-card" data-type="financeiro">
        <h3>Relatório Financeiro</h3>
        <p>Análise financeira e fluxo de caixa</p>
        <form class="report-form">
            <div class="form-group">
                <label>Tipo de Relatório</label>
                <select name="tipo_relatorio" class="form-control">
                    <option value="faturamento">Faturamento</option>
                    <option value="custos">Custos</option>
                    <option value="lucros">Lucros e Perdas</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Gerar Relatório</button>
        </form>
    </div>
</div>
