<div class="config-container">
    <div class="page-header">
        <h2>Configurações do Sistema</h2>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="salvarConfiguracoes()">Salvar Alterações</button>
            <button class="btn btn-outline" onclick="exportarConfiguracoes()">Exportar</button>
        </div>
    </div>

    <div class="config-grid">
        <div class="config-section card">
            <h3>Configurações Gerais</h3>
            <form id="configGerais" class="config-form">
                <div class="form-group">
                    <label>Nome da Empresa</label>
                    <input type="text" name="empresa_nome" value="<?= $config['empresa_nome'] ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label>Marca D'água em PDFs</label>
                    <div class="upload-area">
                        <input type="file" name="watermark" accept="image/*" class="file-input">
                        <?php if ($config['watermark']): ?>
                            <img src="<?= $config['watermark'] ?>" class="preview-img">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Moeda</label>
                    <select name="moeda" class="form-control">
                        <option value="BRL" <?= $config['moeda'] == 'BRL' ? 'selected' : '' ?>>Real (R$)</option>
                        <option value="USD" <?= $config['moeda'] == 'USD' ? 'selected' : '' ?>>Dólar ($)</option>
                        <option value="EUR" <?= $config['moeda'] == 'EUR' ? 'selected' : '' ?>>Euro (€)</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="config-section card">
            <h3>Limites e Restrições</h3>
            <form id="configLimites" class="config-form">
                <div class="form-group">
                    <label>Limite de Arquivos (MB)</label>
                    <input type="number" name="limite_arquivo" value="<?= $config['limite_arquivo'] ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label>Formatos Permitidos</label>
                    <div class="formato-grid">
                        <?php foreach ($formatos_arquivo as $formato): ?>
                        <label class="checkbox-container">
                            <input type="checkbox" name="formatos[]" 
                                   value="<?= $formato['extensao'] ?>"
                                   <?= in_array($formato['extensao'], $config['formatos']) ? 'checked' : '' ?>>
                            <span class="checkmark"></span>
                            <?= strtoupper($formato['extensao']) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </form>
        </div>

        <div class="config-section card">
            <h3>Integrações</h3>
            <div class="integracao-lista">
                <?php foreach ($integracoes as $int): ?>
                <div class="integracao-item">
                    <div class="integracao-header">
                        <span class="nome"><?= htmlspecialchars($int['nome']) ?></span>
                        <label class="switch">
                            <input type="checkbox" name="integracao_<?= $int['id'] ?>" 
                                   <?= $int['ativo'] ? 'checked' : '' ?>
                                   onchange="toggleIntegracao(<?= $int['id'] ?>)">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="integracao-config" style="display: <?= $int['ativo'] ? 'block' : 'none' ?>">
                        <div class="form-group">
                            <label>API Key</label>
                            <div class="input-group">
                                <input type="password" value="<?= $int['api_key'] ?>" class="form-control">
                                <button type="button" class="btn btn-outline" onclick="toggleSenha(this)">
                                    <i class="icon-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Webhook URL</label>
                            <input type="url" value="<?= $int['webhook_url'] ?>" class="form-control">
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
