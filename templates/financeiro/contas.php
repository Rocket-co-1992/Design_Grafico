<div class="contas-container">
    <div class="contas-header">
        <div class="page-tabs">
            <button class="tab-btn active" data-tab="receber">Contas a Receber</button>
            <button class="tab-btn" data-tab="pagar">Contas a Pagar</button>
        </div>
        <button class="btn btn-primary" onclick="novaConta()">Nova Conta</button>
    </div>

    <div class="contas-content">
        <div class="filtros-bar">
            <div class="filtro-grupo">
                <label>Status</label>
                <select class="form-control" id="statusFiltro">
                    <option value="">Todos</option>
                    <option value="pendente">Pendente</option>
                    <option value="pago">Pago</option>
                    <option value="atrasado">Atrasado</option>
                </select>
            </div>

            <div class="filtro-grupo">
                <label>Período</label>
                <div class="input-grupo">
                    <input type="date" class="form-control" id="dataInicio">
                    <span>até</span>
                    <input type="date" class="form-control" id="dataFim">
                </div>
            </div>
        </div>

        <div class="contas-tabela card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Vencimento</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="contasLista">
                    <!-- Preenchido via JavaScript -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">Total:</td>
                        <td colspan="3" id="totalContas">R$ 0,00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
