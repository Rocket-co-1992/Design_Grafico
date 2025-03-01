<?php require_once '../config/config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Sistema Gráfica' ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="wrapper">
        <?php include 'header.php'; ?>
        
        <main class="main-content">
            <aside class="sidebar">
                <?php include 'sidebar.php'; ?>
            </aside>
            
            <div class="content">
                <?php if (isset($flashMessage)): ?>
                    <div class="alert alert-<?= $flashMessage['type'] ?>">
                        <?= $flashMessage['message'] ?>
                    </div>
                <?php endif; ?>
                
                <?= $content ?? '' ?>
            </div>
        </main>
        
        <?php include 'footer.php'; ?>
    </div>
    <script src="/assets/js/main.js"></script>
</body>
</html>
