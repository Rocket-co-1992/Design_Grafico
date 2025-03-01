<div class="content-header">
    <h2>Clientes</h2>
    <div class="content-header-actions">
        <a href="/clientes/novo" class="btn btn-primary">Novo Cliente</a>
    </div>
</div>

<div class="card">
    <div class="card-filters">
        <form class="filter-form">
            <div class="form-row">
                <div class="form-group">
                    <input type="text" name="busca" placeholder="Buscar cliente..." class="form-control">
                </div>
                <div class="form-group">
                    <select name="status" class="form-control">
                        <option value="">Todos os status</option>
                        <option value="ativo">Ativo</option>
                        <option value="inativo">Inativo</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary">Filtrar</button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Status</th>
                    <th>Último Pedido</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?= $cliente['id'] ?></td>
                    <td><?= htmlspecialchars($cliente['nome']) ?></td>
                    <td><?= htmlspecialchars($cliente['email']) ?></td>
                    <td><?= htmlspecialchars($cliente['telefone']) ?></td>
                    <td><span class="badge badge-<?= $cliente['status'] ?>"><?= $cliente['status'] ?></span></td>
                    <td><?= $cliente['ultimo_pedido'] ? date('d/m/Y', strtotime($cliente['ultimo_pedido'])) : '-' ?></td>
                    <td class="actions">
                        <a href="/clientes/ver/<?= $cliente['id'] ?>" class="btn btn-sm btn-info">Ver</a>
                        <a href="/clientes/editar/<?= $cliente['id'] ?>" class="btn btn-sm btn-secondary">Editar</a>
                        <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="<?= $cliente['id'] ?>">Excluir</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPaginas > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="?pagina=<?= $i ?>" class="page-link <?= $paginaAtual == $i ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>
