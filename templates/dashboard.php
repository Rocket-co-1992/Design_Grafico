<div class="dashboard">
    <div class="grid">
        <div class="card">
            <h3>Pedidos Hoje</h3>
            <div class="card-content">
                <div class="stat-number"><?= $pedidosHoje ?></div>
                <div class="stat-label">Novos Pedidos</div>
            </div>
        </div>

        <div class="card">
            <h3>Em Produção</h3>
            <div class="card-content">
                <div class="stat-number"><?= $emProducao ?></div>
                <div class="stat-label">Pedidos</div>
            </div>
        </div>

        <div class="card">
            <h3>Faturamento</h3>
            <div class="card-content">
                <div class="stat-number">R$ <?= number_format($faturamento, 2, ',', '.') ?></div>
                <div class="stat-label">Último Mês</div>
            </div>
        </div>
    </div>

    <div class="dashboard-section">
        <h2>Últimos Pedidos</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Produto</th>
                    <th>Status</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ultimosPedidos as $pedido): ?>
                <tr>
                    <td>#<?= $pedido['id'] ?></td>
                    <td><?= htmlspecialchars($pedido['cliente']) ?></td>
                    <td><?= htmlspecialchars($pedido['produto']) ?></td>
                    <td><span class="status status-<?= $pedido['status'] ?>"><?= $pedido['status'] ?></span></td>
                    <td><?= date('d/m/Y', strtotime($pedido['data'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
