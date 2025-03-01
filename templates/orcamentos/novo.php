<div class="orcamento-container">
    <div class="page-header">
        <h2><?= isset($orcamento) ? 'Editar Orçamento #'.$orcamento['id'] : 'Novo Orçamento' ?></h2>
    </div>

    <form id="formOrcamento" class="orcamento-form" method="POST">
        <div class="form-grid">
            <div class="card">
                <h3>Informações do Cliente</h3>
                <div class="cliente-search">
                    <div class="form-group">
                        <label>Buscar Cliente</label>
                        <div class="search-input">
                            <input type="text" id="clienteSearch" class="form-control" 
                                   placeholder="Digite nome, email ou CNPJ...">
                            <div id="clienteResults" class="search-results"></div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline" onclick="novoCliente()">
                        + Novo Cliente
                    </button>
                </div>
                
                <div id="clienteSelecionado" class="cliente-info" style="display: none;">
                    <div class="info-header">
                        <h4>Cliente Selecionado</h4>
                        <button type="button" class="btn-link" onclick="alterarCliente()">Alterar</button>
                    </div>
                    <div class="info-content">
                        <!-- Preenchido via JavaScript -->
                    </div>
                </div>
            </div>

            <div class="card produtos-card">
                <h3>Produtos</h3>
                <div id="listaProdutos">
                    <div class="produto-item" data-index="0">
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label>Produto</label>
                                <select name="produtos[0][id]" class="form-control produto-select" required>
                                    <option value="">Selecione um produto</option>
                                    <?php foreach ($produtos as $p): ?>
                                        <option value="<?= $p['id'] ?>" 
                                                data-base-price="<?= $p['preco_base'] ?>"
                                                data-min-qty="<?= $p['qtd_minima'] ?>">
                                            <?= htmlspecialchars($p['nome']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-2">
                                <label>Quantidade</label>
                                <input type="number" name="produtos[0][quantidade]" min="1" 
                                       class="form-control quantidade-input" required>
                            </div>
                            <div class="form-group col-2">
                                <label>Valor Unit.</label>
                                <input type="text" name="produtos[0][valor_unit]" 
                                       class="form-control money valor-unit" readonly>
                            </div>
                            <div class="form-group col-2">
                                <label>Subtotal</label>
                                <input type="text" class="form-control subtotal" readonly>
                            </div>
                        </div>
                        <div class="opcoes-container">
                            <!-- Opções do produto carregadas via AJAX -->
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline" onclick="adicionarProduto()">
                    + Adicionar Produto
                </button>
            </div>

            <div class="card">
                <h3>Observações e Condições</h3>
                <div class="form-group">
                    <label>Observações para o Cliente</label>
                    <textarea name="observacoes" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Condições de Pagamento</label>
                    <select name="condicao_pagamento" class="form-control" required>
                        <option value="vista">À Vista</option>
                        <option value="2x">2x sem juros</option>
                        <option value="3x">3x sem juros</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="totais-section">
            <div class="totais-grid">
                <div class="total-item">
                    <span>Subtotal:</span>
                    <strong id="subtotalGeral">R$ 0,00</strong>
                </div>
                <div class="total-item">
                    <span>Desconto:</span>
                    <div class="desconto-input">
                        <input type="number" name="desconto_percentual" min="0" max="100" step="0.1" value="0">
                        <span>%</span>
                    </div>
                </div>
                <div class="total-item total-final">
                    <span>Total:</span>
                    <strong id="totalGeral">R$ 0,00</strong>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Salvar Orçamento</button>
            <button type="button" class="btn btn-outline" onclick="visualizarPDF()">
                Visualizar PDF
            </button>
        </div>
    </form>
</div>
