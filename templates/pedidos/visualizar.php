<div class="content-header">
    <h2>Pedido #<?= $pedido['id'] ?></h2>
    <div class="content-header-actions">
        <a href="/pedidos/editar/<?= $pedido['id'] ?>" class="btn btn-secondary">Editar</a>
        <button class="btn btn-primary" onclick="window.print()">Imprimir</button>
    </div>
</div>

<div class="pedido-container">
    <div class="pedido-info">
        <div class="card info-cliente">
            <h3>Informações do Cliente</h3>
            <div class="info-grupo">
                <label>Nome:</label>
                <span><?= htmlspecialchars($pedido['cliente_nome']) ?></span>
            </div>
            <div class="info-grupo">
                <label>Email:</label>
                <span><?= htmlspecialchars($pedido['cliente_email']) ?></span>
            </div>
            <div class="info-grupo">
                <label>Telefone:</label>
                <span><?= htmlspecialchars($pedido['cliente_telefone']) ?></span>
            </div>
        </div>

        <div class="card status-pedido">
            <h3>Status do Pedido</h3>
            <div class="timeline">
                <?php foreach ($historico as $evento): ?>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h4><?= htmlspecialchars($evento['status']) ?></h4>
                        <time><?= date('d/m/Y H:i', strtotime($evento['data'])) ?></time>
                        <?php if ($evento['observacao']): ?>
                            <p><?= htmlspecialchars($evento['observacao']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="card pedido-itens">
        <h3>Itens do Pedido</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor Unit.</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['produto_nome']) ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                    <td><strong>R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
