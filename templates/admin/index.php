<?php require_once '../includes/header.php'; ?>

<div class="dashboard-grid">
    <div class="quick-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-info">
                <h3>Pedidos Hoje</h3>
                <p class="stat-number"><?= $stats['pedidos_hoje'] ?></p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-print"></i>
            </div>
            <div class="stat-info">
                <h3>Em Produção</h3>
                <p class="stat-number"><?= $stats['em_producao'] ?></p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>Clientes Ativos</h3>
                <p class="stat-number"><?= $stats['clientes_ativos'] ?></p>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="card">
            <h2>Últimos Pedidos</h2>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimos_pedidos as $pedido): ?>
                        <tr>
                            <td>#<?= $pedido['id'] ?></td>
                            <td><?= htmlspecialchars($pedido['cliente']) ?></td>
                            <td>R$ <?= number_format($pedido['valor'], 2, ',', '.') ?></td>
                            <td><span class="status status-<?= $pedido['status'] ?>"><?= $pedido['status'] ?></span></td>
                            <td>
                                <a href="/pedidos/ver/<?= $pedido['id'] ?>" class="btn btn-sm btn-info">Ver</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
