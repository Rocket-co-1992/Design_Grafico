<div class="notificacoes-centro">
    <div class="notif-header">
        <h2>Centro de Notificações</h2>
        <div class="notif-acoes">
            <button class="btn btn-outline" onclick="marcarTodasLidas()">
                Marcar todas como lidas
            </button>
            <div class="filtro-tipo">
                <select class="form-control" onchange="filtrarNotificacoes(this.value)">
                    <option value="">Todos os tipos</option>
                    <option value="pedido">Pedidos</option>
                    <option value="producao">Produção</option>
                    <option value="qualidade">Qualidade</option>
                    <option value="sistema">Sistema</option>
                </select>
            </div>
        </div>
    </div>

    <div class="notif-lista">
        <?php foreach ($notificacoes as $notif): ?>
        <div class="notif-item <?= $notif['lida'] ? 'lida' : '' ?>" data-tipo="<?= $notif['tipo'] ?>">
            <div class="notif-icone">
                <i class="icon-<?= $notif['tipo'] ?>"></i>
            </div>
            <div class="notif-conteudo">
                <div class="notif-titulo"><?= htmlspecialchars($notif['titulo']) ?></div>
                <div class="notif-mensagem"><?= htmlspecialchars($notif['mensagem']) ?></div>
                <div class="notif-meta">
                    <span class="notif-tempo" title="<?= date('d/m/Y H:i', strtotime($notif['created_at'])) ?>">
                        <?= $notif['tempo_relativo'] ?>
                    </span>
                    <?php if ($notif['link']): ?>
                        <a href="<?= $notif['link'] ?>" class="notif-link">Ver detalhes</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="notif-acoes">
                <?php if (!$notif['lida']): ?>
                <button class="btn-icon" onclick="marcarLida(<?= $notif['id'] ?>)" title="Marcar como lida">
                    <i class="icon-check"></i>
                </button>
                <?php endif; ?>
                <button class="btn-icon" onclick="removerNotificacao(<?= $notif['id'] ?>)" title="Remover">
                    <i class="icon-trash"></i>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if ($total_paginas > 1): ?>
    <div class="notif-paginacao">
        <button class="btn btn-outline" <?= $pagina_atual == 1 ? 'disabled' : '' ?> 
                onclick="carregarPagina(<?= $pagina_atual - 1 ?>)">
            Anterior
        </button>
        <span class="pagina-info">Página <?= $pagina_atual ?> de <?= $total_paginas ?></span>
        <button class="btn btn-outline" <?= $pagina_atual == $total_paginas ? 'disabled' : '' ?>
                onclick="carregarPagina(<?= $pagina_atual + 1 ?>)">
            Próxima
        </button>
    </div>
    <?php endif; ?>
</div>
