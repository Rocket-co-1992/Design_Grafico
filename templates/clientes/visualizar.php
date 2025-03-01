<div class="cliente-container">
    <div class="page-header">
        <h2>Cliente: <?= htmlspecialchars($cliente['nome']) ?></h2>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="editarCliente(<?= $cliente['id'] ?>)">Editar</button>
            <button class="btn btn-outline" onclick="enviarMensagem(<?= $cliente['id'] ?>)">Mensagem</button>
        </div>
    </div>

    <div class="cliente-grid">
        <div class="card info-basica">
            <h3>Informações Básicas</h3>
            <div class="info-grupo">
                <label>Email:</label>
                <span><?= htmlspecialchars($cliente['email']) ?></span>
                <label>Telefone:</label>
                <span><?= htmlspecialchars($cliente['telefone']) ?></span>
                <label>CNPJ:</label>
                <span><?= htmlspecialchars($cliente['cnpj']) ?></span>
                <label>Cadastrado em:</label>
                <span><?= date('d/m/Y', strtotime($cliente['created_at'])) ?></span>
            </div>
        </div>

        <div class="card fidelidade-status">
            <h3>Programa de Fidelidade</h3>
            <div class="pontos-container">
                <div class="pontos-atuais">
                    <span class="numero"><?= $fidelidade['pontos'] ?></span>
                    <span class="label">Pontos</span>
                </div>
                <div class="nivel-atual">
                    <span class="badge nivel-<?= $fidelidade['nivel'] ?>">
                        <?= ucfirst($fidelidade['nivel']) ?>
                    </span>
                </div>
            </div>
            <div class="progresso-nivel">
                <div class="barra-progresso">
                    <div class="progresso" style="width: <?= $fidelidade['progresso'] ?>%"></div>
                </div>
                <span class="meta">Faltam <?= $fidelidade['pontos_proxnivel'] ?> pontos para o próximo nível</span>
            </div>
        </div>
    </div>

    <div class="card historico-pedidos">
        <h3>Histórico de Pedidos</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Data</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th>Pontos</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td>#<?= $pedido['id'] ?></td>
                        <td><?= date('d/m/Y', strtotime($pedido['data'])) ?></td>
                        <td>R$ <?= number_format($pedido['valor'], 2, ',', '.') ?></td>
                        <td><span class="status-badge status-<?= $pedido['status'] ?>"><?= $pedido['status'] ?></span></td>
                        <td>+<?= $pedido['pontos'] ?></td>
                        <td><a href="/pedidos/ver/<?= $pedido['id'] ?>" class="btn btn-sm btn-info">Ver</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
