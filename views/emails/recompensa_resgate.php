<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; }
        .content { padding: 20px; }
        .footer { text-align: center; padding: 20px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo SITE_NAME; ?></h1>
        </div>
        
        <div class="content">
            <h2>Olá <?php echo $dados['nome']; ?>,</h2>
            
            <p>Seu resgate foi confirmado com sucesso!</p>
            
            <h3>Detalhes do Resgate:</h3>
            <ul>
                <li>Recompensa: <?php echo $dados['recompensa']; ?></li>
                <li>Pontos utilizados: <?php echo $dados['pontos']; ?></li>
            </ul>
            
            <p>Em breve você receberá instruções sobre como utilizar sua recompensa.</p>
        </div>
        
        <div class="footer">
            <p>Este é um email automático, não responda.</p>
        </div>
    </div>
</body>
</html>
