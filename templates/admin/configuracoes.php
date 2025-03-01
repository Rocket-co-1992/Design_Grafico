<div class="config-container">
    <div class="page-header">
        <h2>Configurações do Sistema</h2>
    </div>

    <div class="config-grid">
        <div class="config-card">
            <h3>Configurações Gerais</h3>
            <form class="config-form" method="POST" action="/admin/salvar-config">
                <div class="form-group">
                    <label>Nome da Empresa</label>
                    <input type="text" name="empresa_nome" value="<?= $config['empresa_nome'] ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label>Email Principal</label>
                    <input type="email" name="empresa_email" value="<?= $config['empresa_email'] ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label>Moeda</label>
                    <select name="moeda" class="form-control">
                        <option value="BRL" <?= $config['moeda'] == 'BRL' ? 'selected' : '' ?>>Real (R$)</option>
                        <option value="USD" <?= $config['moeda'] == 'USD' ? 'selected' : '' ?>>Dólar ($)</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </form>
        </div>

        <div class="config-card">
            <h3>Configurações de Produção</h3>
            <form class="config-form" method="POST" action="/admin/salvar-config-producao">
                <div class="form-group">
                    <label>Limite de Pedidos/Dia</label>
                    <input type="number" name="limite_pedidos" value="<?= $config['limite_pedidos'] ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label>Tempo Mínimo de Produção (horas)</label>
                    <input type="number" name="tempo_minimo" value="<?= $config['tempo_minimo'] ?>" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </form>
        </div>

        <div class="config-card">
            <h3>Notificações</h3>
            <form class="config-form" method="POST" action="/admin/salvar-config-notificacoes">
                <div class="form-group">
                    <label class="checkbox-container">
                        <input type="checkbox" name="notif_novos_pedidos" <?= $config['notif_novos_pedidos'] ? 'checked' : '' ?>>
                        <span class="checkmark"></span>
                        Notificar novos pedidos
                    </label>
                </div>

                <div class="form-group">
                    <label class="checkbox-container">
                        <input type="checkbox" name="notif_atrasos" <?= $config['notif_atrasos'] ? 'checked' : '' ?>>
                        <span class="checkmark"></span>
                        Notificar atrasos na produção
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Salvar Preferências</button>
            </form>
        </div>
    </div>
</div>
