<aside class="sidebar">
    <nav class="sidebar-nav">
        <div class="user-profile">
            <img src="<?= $_SESSION['user_avatar'] ?? '/assets/img/default-avatar.png' ?>" alt="Avatar">
            <span><?= htmlspecialchars($_SESSION['user_name']) ?></span>
        </div>
        
        <div class="menu-section">
            <h3>Principal</h3>
            <ul>
                <li><a href="/dashboard"><i class="icon-dashboard"></i>Dashboard</a></li>
                <li><a href="/producao"><i class="icon-print"></i>Produção</a></li>
                <li><a href="/orcamentos"><i class="icon-calculator"></i>Orçamentos</a></li>
                <li><a href="/pedidos"><i class="icon-shopping-cart"></i>Pedidos</a></li>
            </ul>
        </div>

        <div class="menu-section">
            <h3>Gestão</h3>
            <ul>
                <li><a href="/clientes"><i class="icon-users"></i>Clientes</a></li>
                <li><a href="/produtos"><i class="icon-box"></i>Produtos</a></li>
                <li><a href="/financeiro"><i class="icon-dollar"></i>Financeiro</a></li>
                <li><a href="/relatorios"><i class="icon-chart"></i>Relatórios</a></li>
            </ul>
        </div>

        <?php if ($_SESSION['user_level'] >= 2): ?>
        <div class="menu-section">
            <h3>Configurações</h3>
            <ul>
                <li><a href="/usuarios"><i class="icon-user-cog"></i>Usuários</a></li>
                <li><a href="/configuracoes"><i class="icon-cog"></i>Sistema</a></li>
            </ul>
        </div>
        <?php endif; ?>
    </nav>
</aside>
