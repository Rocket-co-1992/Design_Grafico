<div class="usuarios-container">
    <div class="page-header">
        <h2>Gestão de Usuários</h2>
        <button class="btn btn-primary" onclick="novoUsuario()">Novo Usuário</button>
    </div>

    <div class="usuarios-grid">
        <aside class="permissoes-sidebar card">
            <h3>Grupos e Permissões</h3>
            <?php foreach ($grupos as $grupo): ?>
            <div class="grupo-permissao">
                <div class="grupo-header">
                    <span class="grupo-nome"><?= htmlspecialchars($grupo['nome']) ?></span>
                    <span class="grupo-total"><?= $grupo['total_usuarios'] ?> usuários</span>
                </div>
                <div class="grupo-permissoes">
                    <?php foreach ($grupo['permissoes'] as $permissao): ?>
                    <span class="badge badge-permissao">
                        <?= htmlspecialchars($permissao['nome']) ?>
                    </span>
                    <?php endforeach; ?>
                </div>
                <button class="btn btn-sm btn-outline" onclick="editarGrupo(<?= $grupo['id'] ?>)">
                    Editar Grupo
                </button>
            </div>
            <?php endforeach; ?>
        </aside>

        <div class="usuarios-lista card">
            <div class="lista-header">
                <div class="lista-filtros">
                    <select class="form-control" onchange="filtrarPorGrupo(this.value)">
                        <option value="">Todos os Grupos</option>
                        <?php foreach ($grupos as $grupo): ?>
                        <option value="<?= $grupo['id'] ?>"><?= htmlspecialchars($grupo['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select class="form-control" onchange="filtrarPorStatus(this.value)">
                        <option value="">Todos os Status</option>
                        <option value="ativo">Ativos</option>
                        <option value="inativo">Inativos</option>
                    </select>
                    <input type="text" class="form-control" placeholder="Buscar usuário..." 
                           onkeyup="buscarUsuario(this.value)">
                </div>
            </div>

            <div class="usuarios-cards">
                <?php foreach ($usuarios as $usuario): ?>
                <div class="usuario-card" data-grupo="<?= $usuario['grupo_id'] ?>" 
                     data-status="<?= $usuario['status'] ?>">
                    <div class="usuario-avatar">
                        <img src="<?= $usuario['avatar'] ?? '/assets/img/default-avatar.png' ?>" 
                             alt="Avatar de <?= htmlspecialchars($usuario['nome']) ?>">
                        <span class="status-indicator status-<?= $usuario['status'] ?>"></span>
                    </div>
                    <div class="usuario-info">
                        <h4><?= htmlspecialchars($usuario['nome']) ?></h4>
                        <span class="usuario-email"><?= htmlspecialchars($usuario['email']) ?></span>
                        <div class="usuario-meta">
                            <span class="grupo"><?= htmlspecialchars($usuario['grupo_nome']) ?></span>
                            <span class="ultimo-acesso">
                                Último acesso: <?= $usuario['ultimo_acesso'] ? 
                                    date('d/m/Y H:i', strtotime($usuario['ultimo_acesso'])) : 'Nunca' ?>
                            </span>
                        </div>
                    </div>
                    <div class="usuario-acoes">
                        <button class="btn btn-sm" onclick="editarUsuario(<?= $usuario['id'] ?>)">
                            Editar
                        </button>
                        <button class="btn btn-sm btn-outline" 
                                onclick="alterarStatus(<?= $usuario['id'] ?>)">
                            <?= $usuario['status'] == 'ativo' ? 'Desativar' : 'Ativar' ?>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
