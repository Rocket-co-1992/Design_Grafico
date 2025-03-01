<div class="fidelidade-container">
    <div class="nivel-status card">
        <div class="nivel-info">
            <div class="nivel-atual">
                <span class="nivel-badge nivel-<?= $cliente['nivel'] ?>">
                    <?= ucfirst($cliente['nivel']) ?>
                </span>
                <span class="pontos-total"><?= number_format($cliente['pontos']) ?> pontos</span>
            </div>
            <div class="progresso-nivel">
                <div class="barra-progresso">
                    <div class="progresso" style="width: <?= $cliente['progresso'] ?>%"></div>
                </div>
                <span class="proxima-meta">
                    Faltam <?= number_format($cliente['pontos_prox_nivel']) ?> pontos para 
                    <?= ucfirst($cliente['prox_nivel']) ?>
                </span>
            </div>
        </div>
        
        <div class="beneficios-atuais">
            <h3>Seus Benefícios</h3>
            <div class="beneficios-grid">
                <?php foreach ($beneficios as $beneficio): ?>
                <div class="beneficio-item">
                    <i class="icon-<?= $beneficio['icone'] ?>"></i>
                    <div class="beneficio-info">
                        <span class="beneficio-titulo"><?= htmlspecialchars($beneficio['titulo']) ?></span>
                        <span class="beneficio-desc"><?= htmlspecialchars($beneficio['descricao']) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="historico-grid">
        <div class="card historico-pontos">
            <h3>Histórico de Pontos</h3>
            <div class="timeline">
                <?php foreach ($historico as $registro): ?>
                <div class="timeline-item">
                    <div class="timeline-icon tipo-<?= $registro['tipo'] ?>">
                        <i class="icon-<?= $registro['tipo'] ?>"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="registro-header">
                            <span class="registro-data">
                                <?= date('d/m/Y', strtotime($registro['data'])) ?>
                            </span>
                            <span class="registro-pontos <?= $registro['pontos'] > 0 ? 'positivo' : 'negativo' ?>">
                                <?= $registro['pontos'] > 0 ? '+' : '' ?><?= $registro['pontos'] ?> pontos
                            </span>
                        </div>
                        <p class="registro-desc"><?= htmlspecialchars($registro['descricao']) ?></p>
                        <?php if ($registro['pedido_id']): ?>
                            <a href="/pedidos/ver/<?= $registro['pedido_id'] ?>" class="registro-link">
                                Ver Pedido #<?= $registro['pedido_id'] ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="card recompensas">
            <h3>Recompensas Disponíveis</h3>
            <div class="recompensas-grid">
                <?php foreach ($recompensas as $recompensa): ?>
                <div class="recompensa-card <?= $cliente['pontos'] >= $recompensa['pontos'] ? 'disponivel' : 'bloqueada' ?>">
                    <div class="recompensa-header">
                        <span class="recompensa-titulo">
                            <?= htmlspecialchars($recompensa['titulo']) ?>
                        </span>
                        <span class="recompensa-pontos">
                            <?= number_format($recompensa['pontos']) ?> pontos
                        </span>
                    </div>
                    <p class="recompensa-desc"><?= htmlspecialchars($recompensa['descricao']) ?></p>
                    <?php if ($cliente['pontos'] >= $recompensa['pontos']): ?>
                        <button class="btn btn-primary" onclick="resgatarRecompensa(<?= $recompensa['id'] ?>)">
                            Resgatar
                        </button>
                    <?php else: ?>
                        <div class="pontos-faltantes">
                            Faltam <?= number_format($recompensa['pontos'] - $cliente['pontos']) ?> pontos
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
