<div class="galeria-container">
    <div class="galeria-header">
        <div class="filtros-grupo">
            <div class="busca-grupo">
                <input type="text" class="form-control" placeholder="Buscar arquivos..." 
                       onkeyup="filtrarArquivos(this.value)">
                <select class="form-control" onchange="filtrarTipo(this.value)">
                    <option value="">Todos os tipos</option>
                    <option value="imagem">Imagens</option>
                    <option value="documento">Documentos</option>
                    <option value="vetor">Arquivos Vetoriais</option>
                </select>
            </div>
            <div class="ordem-grupo">
                <button class="btn btn-outline" onclick="alternarVisualizacao()">
                    <i class="icon-grid"></i>
                </button>
                <select class="form-control" onchange="ordenarArquivos(this.value)">
                    <option value="recente">Mais Recentes</option>
                    <option value="nome">Nome (A-Z)</option>
                    <option value="tamanho">Tamanho</option>
                </select>
            </div>
        </div>
        <div class="upload-area" id="dropZone">
            <i class="icon-upload"></i>
            <span>Arraste arquivos ou clique para fazer upload</span>
            <input type="file" multiple class="file-input" onchange="handleFiles(this.files)">
        </div>
    </div>

    <div class="arquivos-grid">
        <?php foreach ($arquivos as $arquivo): ?>
        <div class="arquivo-card" data-tipo="<?= $arquivo['tipo'] ?>">
            <div class="arquivo-preview">
                <?php if ($arquivo['tipo'] == 'imagem'): ?>
                    <img src="<?= $arquivo['thumbnail'] ?>" alt="<?= htmlspecialchars($arquivo['nome']) ?>">
                <?php else: ?>
                    <i class="icon-file-<?= $arquivo['icone'] ?>"></i>
                <?php endif; ?>
                <div class="arquivo-overlay">
                    <button class="btn-icon" onclick="visualizarArquivo(<?= $arquivo['id'] ?>)">
                        <i class="icon-eye"></i>
                    </button>
                    <button class="btn-icon" onclick="baixarArquivo(<?= $arquivo['id'] ?>)">
                        <i class="icon-download"></i>
                    </button>
                    <button class="btn-icon" onclick="excluirArquivo(<?= $arquivo['id'] ?>)">
                        <i class="icon-trash"></i>
                    </button>
                </div>
            </div>

            <div class="arquivo-info">
                <div class="arquivo-principal">
                    <span class="arquivo-nome" title="<?= htmlspecialchars($arquivo['nome']) ?>">
                        <?= htmlspecialchars($arquivo['nome']) ?>
                    </span>
                    <span class="arquivo-data">
                        <?= date('d/m/Y', strtotime($arquivo['data_upload'])) ?>
                    </span>
                </div>
                <div class="arquivo-meta">
                    <span class="arquivo-tamanho"><?= $arquivo['tamanho_formatado'] ?></span>
                    <span class="arquivo-extensao"><?= strtoupper($arquivo['extensao']) ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div id="previewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="previewNome"></h3>
                <button class="btn-close" onclick="fecharPreview()">&times;</button>
            </div>
            <div class="modal-body">
                <div id="previewContainer"></div>
            </div>
            <div class="modal-footer">
                <div id="previewInfo"></div>
                <div class="modal-acoes">
                    <button class="btn btn-primary" onclick="baixarArquivoAtual()">
                        <i class="icon-download"></i> Baixar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
