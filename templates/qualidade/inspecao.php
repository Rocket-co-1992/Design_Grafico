<div class="inspecao-container">
    <div class="page-header">
        <h2>Inspeção de Qualidade</h2>
        <div class="ordem-info">
            <span class="ordem-numero">OP #<?= $ordem['id'] ?></span>
            <span class="produto-nome"><?= htmlspecialchars($ordem['produto_nome']) ?></span>
        </div>
    </div>

    <div class="inspecao-grid">
        <form class="inspecao-form" method="POST" action="/qualidade/salvar-inspecao">
            <input type="hidden" name="ordem_id" value="<?= $ordem['id'] ?>">
            
            <div class="card parametros-card">
                <h3>Parâmetros de Qualidade</h3>
                <div class="parametros-grid">
                    <?php foreach ($parametros as $param): ?>
                    <div class="parametro-item">
                        <div class="param-header">
                            <label><?= htmlspecialchars($param['nome']) ?></label>
                            <?php if ($param['critico']): ?>
                                <span class="badge badge-danger">Crítico</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="param-valores">
                            <div class="valor-esperado">
                                Esperado: <?= htmlspecialchars($param['valor_esperado']) ?>
                                <?= $param['unidade'] ?>
                            </div>
                            <div class="valor-medido">
                                <label>Medido:</label>
                                <input type="number" 
                                       name="parametros[<?= $param['id'] ?>]" 
                                       step="<?= $param['precisao'] ?>"
                                       class="form-control"
                                       required>
                                <span class="unidade"><?= $param['unidade'] ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card conformidade-card">
                <h3>Checklist de Conformidade</h3>
                <div class="checklist-grid">
                    <?php foreach ($checklist as $item): ?>
                    <div class="checklist-item">
                        <label class="checkbox-container">
                            <input type="checkbox" 
                                   name="checklist[<?= $item['id'] ?>]" 
                                   value="1">
                            <span class="checkmark"></span>
                            <?= htmlspecialchars($item['descricao']) ?>
                        </label>
                        <?php if ($item['ajuda']): ?>
                            <span class="help-tooltip" title="<?= htmlspecialchars($item['ajuda']) ?>">?</span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card observacoes-card">
                <h3>Observações e Não Conformidades</h3>
                <textarea name="observacoes" 
                          class="form-control" 
                          rows="4"
                          placeholder="Registre aqui qualquer observação ou não conformidade encontrada..."></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Registrar Inspeção</button>
                <button type="button" class="btn btn-danger" onclick="reportarProblema()">
                    Reportar Problema
                </button>
            </div>
        </form>

        <div class="card historico-inspecoes">
            <h3>Histórico de Inspeções</h3>
            <div class="timeline">
                <?php foreach ($historico as $insp): ?>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="insp-header">
                            <span class="insp-data">
                                <?= date('d/m/Y H:i', strtotime($insp['data'])) ?>
                            </span>
                            <span class="insp-status status-<?= $insp['status'] ?>">
                                <?= ucfirst($insp['status']) ?>
                            </span>
                        </div>
                        <div class="insp-detalhes">
                            <?= htmlspecialchars($insp['observacoes']) ?>
                        </div>
                        <div class="insp-footer">
                            Por: <?= htmlspecialchars($insp['inspetor_nome']) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
