<?php
require_once '../../config/config.php';
require_once '../../models/ProdutoPersonalizavel.php';

$produto = new ProdutoPersonalizavel();
$id = $_GET['id'] ?? 0;
$detalhes = $produto->getDetalhes($id);

if (!$detalhes) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $detalhes['nome']; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/designer.css">
    <script src="../../assets/js/fabric.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Editor de Design -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Personalize seu Produto</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <!-- Ferramentas de Edição -->
                                <div class="design-tools">
                                    <button class="btn btn-sm btn-primary" onclick="addText()">
                                        <i class="fas fa-text"></i> Adicionar Texto
                                    </button>
                                    <button class="btn btn-sm btn-primary" onclick="addImage()">
                                        <i class="fas fa-image"></i> Adicionar Imagem
                                    </button>
                                    <hr>
                                    <div class="tool-group">
                                        <label>Fonte</label>
                                        <select id="fontFamily" onchange="updateText()">
                                            <option value="Arial">Arial</option>
                                            <option value="Times New Roman">Times New Roman</option>
                                            <option value="Courier New">Courier New</option>
                                        </select>
                                    </div>
                                    <div class="tool-group">
                                        <label>Tamanho</label>
                                        <input type="number" id="fontSize" value="20" onchange="updateText()">
                                    </div>
                                    <div class="tool-group">
                                        <label>Cor</label>
                                        <input type="color" id="textColor" onchange="updateText()">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <!-- Área do Canvas -->
                                <canvas id="designCanvas"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Opções do Produto -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4><?php echo $detalhes['nome']; ?></h4>
                    </div>
                    <div class="card-body">
                        <p class="product-price">
                            R$ <?php echo number_format($detalhes['preco'], 2, ',', '.'); ?>
                        </p>
                        <form id="productForm">
                            <?php foreach ($detalhes['opcoes'] as $opcao): ?>
                                <div class="form-group">
                                    <label><?php echo $opcao['nome']; ?></label>
                                    <?php if ($opcao['tipo'] == 'select'): ?>
                                        <select name="opcao[<?php echo $opcao['id']; ?>]" class="form-control" 
                                                <?php echo $opcao['obrigatorio'] ? 'required' : ''; ?>>
                                            <option value="">Selecione...</option>
                                            <?php foreach ($opcao['valores'] as $valor): ?>
                                                <option value="<?php echo $valor['id']; ?>" 
                                                        data-preco="<?php echo $valor['preco_adicional']; ?>">
                                                    <?php echo $valor['valor']; ?>
                                                    <?php if ($valor['preco_adicional'] > 0): ?>
                                                        (+R$ <?php echo number_format($valor['preco_adicional'], 2, ',', '.'); ?>)
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                            
                            <div class="form-group mt-4">
                                <button type="button" class="btn btn-primary btn-lg btn-block" onclick="salvarDesign()">
                                    Adicionar ao Carrinho
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-block" onclick="salvarRascunho()">
                                    Salvar Rascunho
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Inicialização do canvas
        var canvas = new fabric.Canvas('designCanvas');
        canvas.setDimensions({width: 600, height: 400});
        
        // Carregar template inicial se existir
        <?php if (!empty($detalhes['templates'])): ?>
        fabric.Image.fromURL('<?php echo $detalhes['templates'][0]['arquivo']; ?>', function(img) {
            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
        });
        <?php endif; ?>
        
        function addText() {
            var text = new fabric.Text('Digite seu texto', {
                left: 100,
                top: 100,
                fontFamily: 'Arial',
                fontSize: 20,
                fill: '#000000'
            });
            canvas.add(text);
            canvas.setActiveObject(text);
        }
        
        function updateText() {
            var obj = canvas.getActiveObject();
            if (obj && obj.type === 'text') {
                obj.set({
                    fontFamily: document.getElementById('fontFamily').value,
                    fontSize: parseInt(document.getElementById('fontSize').value),
                    fill: document.getElementById('textColor').value
                });
                canvas.renderAll();
            }
        }
        
        function salvarDesign() {
            // Capturar preview do design
            var preview = canvas.toDataURL();
            
            // Capturar dados do design
            var design = {
                json: JSON.stringify(canvas.toJSON()),
                opcoes: serializeForm('productForm')
            };
            
            // Enviar para o servidor
            fetch('../../api/designs/salvar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    produto_id: <?php echo $id; ?>,
                    design: design,
                    preview: preview
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'carrinho.php';
                }
            });
        }
    </script>
</body>
</html>
