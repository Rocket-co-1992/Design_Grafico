<div class="orcamento-avancado">
    <div class="page-header">
        <h2>Orçamento Personalizado</h2>
        <div class="header-actions">
            <button class="btn btn-outline" onclick="salvarRascunho()">Salvar Rascunho</button>
            <button class="btn btn-primary" onclick="enviarOrcamento()">Enviar Orçamento</button>
        </div>
    </div>

    <div class="orcamento-grid">
        <div class="configuracao-produto card">
            <div class="produto-selector">
                <h3>Especificações do Produto</h3>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Tipo de Produto</label>
                        <select class="form-control" id="tipoProduto" onchange="carregarEspecificacoes()">
                            <?php foreach ($tipos_produto as $tipo): ?>
                            <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Formato</label>
                        <select class="form-control" id="formatoProduto">
                            <?php foreach ($formatos as $formato): ?>
                            <option value="<?= $formato['id'] ?>"><?= htmlspecialchars($formato['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div id="especificacoesDinamicas">
                    <!-- Preenchido via JavaScript -->
                </div>
            </div>

            <div class="acabamentos-section">
                <h3>Acabamentos</h3>
                <div class="acabamentos-grid">
                    <?php foreach ($acabamentos as $acabamento): ?>
                    <div class="acabamento-item">
                        <label class="checkbox-custom">
                            <input type="checkbox" name="acabamentos[]" 
                                   value="<?= $acabamento['id'] ?>"
                                   onchange="calcularPreco()">
                            <span class="checkmark"></span>
                            <?= htmlspecialchars($acabamento['nome']) ?>
                        </label>
                        <?php if ($acabamento['descricao']): ?>
                        <span class="info-tooltip" data-tooltip="<?= htmlspecialchars($acabamento['descricao']) ?>">
                            <i class="icon-info"></i>
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="preview-section">
            <div class="preview-card card">
                <h3>Visualização</h3>
                <div class="preview-container">
                    <canvas id="previewCanvas"></canvas>
                </div>
                <div class="preview-actions">
                    <button type="button" class="btn btn-sm" onclick="rotatePreview()">
                        <i class="icon-rotate"></i> Rotacionar
                    </button>
                    <button type="button" class="btn btn-sm" onclick="zoomPreview()">
                        <i class="icon-zoom"></i> Zoom
                    </button>
                </div>
            </div>

            <div class="calculo-card card">
                <h3>Cálculo de Preço</h3>
                <div class="calculo-content">
                    <div class="quantidade-input">
                        <label>Quantidade</label>
                        <div class="input-group">
                            <button type="button" onclick="ajustarQuantidade(-1)">-</button>
                            <input type="number" id="quantidade" value="100" min="1" 
                                   onchange="calcularPreco()">
                            <button type="button" onclick="ajustarQuantidade(1)">+</button>
                        </div>
                    </div>

                    <div class="breakdowns">
                        <div class="breakdown-item">
                            <span>Material:</span>
                            <span id="custoMaterial">R$ 0,00</span>
                        </div>
                        <div class="breakdown-item">
                            <span>Impressão:</span>
                            <span id="custoImpressao">R$ 0,00</span>
                        </div>
                        <div class="breakdown-item">
                            <span>Acabamentos:</span>
                            <span id="custoAcabamentos">R$ 0,00</span>
                        </div>
                        <div class="breakdown-total">
                            <span>Total:</span>
                            <span id="precoTotal">R$ 0,00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
