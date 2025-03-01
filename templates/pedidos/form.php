<div class="content-header">
    <h2><?= isset($pedido) ? 'Editar Pedido #'.$pedido['id'] : 'Novo Pedido' ?></h2>
</div>

<form class="form-pedido" method="POST" action="/pedidos/<?= isset($pedido) ? 'editar/'.$pedido['id'] : 'novo' ?>">
    <div class="form-grid">
        <div class="form-section">
            <h3>Dados do Cliente</h3>
            <div class="form-group">
                <label>Cliente</label>
                <select name="cliente_id" required class="form-control select2">
                    <option value="">Selecione um cliente</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= $cliente['id'] ?>" <?= isset($pedido) && $pedido['cliente_id'] == $cliente['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cliente['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-section">
            <h3>Itens do Pedido</h3>
            <div id="itens-pedido">
                <!-- Template para novos itens será clonado via JavaScript -->
                <div class="item-pedido">
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label>Produto</label>
                            <select name="itens[0][produto_id]" required class="form-control produto-select">
                                <option value="">Selecione um produto</option>
                                <?php foreach ($produtos as $produto): ?>
                                    <option value="<?= $produto['id'] ?>" data-preco="<?= $produto['preco'] ?>">
                                        <?= htmlspecialchars($produto['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-2">
                            <label>Quantidade</label>
                            <input type="number" name="itens[0][quantidade]" min="1" value="1" class="form-control qtd-input">
                        </div>
                        <div class="form-group col-2">
                            <label>Valor Unit.</label>
                            <input type="text" name="itens[0][valor_unitario]" class="form-control money valor-unit">
                        </div>
                        <div class="form-group col-2">
                            <label>Subtotal</label>
                            <input type="text" readonly class="form-control subtotal">
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-add-item">+ Adicionar Item</button>
        </div>

        <div class="form-section">
            <h3>Totais</h3>
            <div class="totais-pedido">
                <div class="row">
                    <div class="col-6">
                        <p>Subtotal: <span id="subtotal">R$ 0,00</span></p>
                        <p>Desconto: <input type="text" name="desconto" class="form-control money" value="0,00"></p>
                        <p>Total: <strong id="total">R$ 0,00</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Salvar Pedido</button>
        <a href="/pedidos" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
