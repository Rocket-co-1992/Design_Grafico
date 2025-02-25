<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .cta-button {
            display: inline-block;
            padding: 10px 20px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .cupom {
            background: #ffc107;
            padding: 10px;
            text-align: center;
            font-size: 24px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo SITE_NAME; ?></h1>
        </div>
        
        <div class="content">
            <h2>Olá <?php echo $dados['nome']; ?>,</h2>
            
            <p>Sentimos sua falta! Como cliente <?php echo ucfirst($dados['nivel']); ?>, 
               você tem benefícios exclusivos:</p>
            
            <ul>
                <li>Desconto especial de <?php echo $dados['desconto']; ?>%</li>
                <li><?php echo $dados['pontos']; ?> pontos disponíveis</li>
                <li>Atendimento prioritário</li>
            </ul>
            
            <div class="cupom">
                Seu cupom exclusivo: <strong><?php echo $dados['cupom']; ?></strong>
            </div>
            
            <p>Este cupom é válido por 30 dias e pode ser usado em qualquer produto.</p>
            
            <a href="<?php echo BASE_URL; ?>/produtos" class="cta-button">
                Aproveitar Agora
            </a>
            
            <p><small>Se precisar de ajuda, estamos à disposição.</small></p>
        </div>
    </div>
</body>
</html>
