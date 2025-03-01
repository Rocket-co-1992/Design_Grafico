<header class="main-header">
    <div class="logo">
        <h1>Gráfica Digital</h1>
    </div>
    
    <nav class="main-nav">
        <ul>
            <li><a href="/dashboard">Dashboard</a></li>
            <li><a href="/pedidos">Pedidos</a></li>
            <li><a href="/producao">Produção</a></li>
            <li><a href="/clientes">Clientes</a></li>
        </ul>
    </nav>
    
    <div class="user-menu">
        <span><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></span>
        <div class="dropdown">
            <a href="/perfil">Perfil</a>
            <a href="/logout">Sair</a>
        </div>
    </div>
</header>
