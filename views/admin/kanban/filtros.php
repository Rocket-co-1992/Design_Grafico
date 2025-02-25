<?php
require_once '../../../config/config.php';
require_once '../../../models/KanbanManager.php';

$kanban = new KanbanManager();
$usuarios = $kanban->getUsuarios();
$etiquetas = $kanban->getEtiquetas();
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Filtros do Quadro</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <div class="modal-body">
            <form id="formFiltros" onsubmit="aplicarFiltros(event)">
                <div class="form-group">
                    <label>Buscar</label>
                    <input type="text" class="form-control" name="busca" 
                           placeholder="Buscar em títulos e descrições...">
                </div>
                
                <div class="form-group">
                    <label>Prioridade</label>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="prioridades[]" value="urgente" id="check-urgente">
                        <label class="custom-control-label" for="check-urgente">Urgente</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="prioridades[]" value="alta" id="check-alta">
                        <label class="custom-control-label" for="check-alta">Alta</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="prioridades[]" value="media" id="check-media">
                        <label class="custom-control-label" for="check-media">Média</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="prioridades[]" value="baixa" id="check-baixa">
                        <label class="custom-control-label" for="check-baixa">Baixa</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Responsável</label>
                    <select class="form-control" name="responsavel">
                        <option value="">Todos</option>
                        <option value="sem">Sem responsável</option>
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?php echo $usuario['id']; ?>">
                                <?php echo $usuario['nome']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Etiquetas</label>
                    <div class="etiquetas-filtro">
                        <?php foreach ($etiquetas as $etiqueta): ?>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       name="etiquetas[]" 
                                       value="<?php echo $etiqueta['id']; ?>"
                                       id="etiqueta-<?php echo $etiqueta['id']; ?>">
                                <label class="custom-control-label" 
                                       for="etiqueta-<?php echo $etiqueta['id']; ?>"
                                       style="color: <?php echo $etiqueta['cor']; ?>">
                                    <?php echo $etiqueta['nome']; ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Prazo</label>
                    <select class="form-control" name="prazo">
                        <option value="">Todos</option>
                        <option value="atrasado">Atrasados</option>
                        <option value="hoje">Para hoje</option>
                        <option value="semana">Esta semana</option>
                        <option value="sem">Sem prazo</option>
                    </select>
                </div>
                
                <div class="text-right">
                    <button type="button" class="btn btn-secondary" onclick="limparFiltros()">
                        Limpar Filtros
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
