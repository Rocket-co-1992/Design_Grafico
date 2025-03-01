<div class="tickets-container">
    <div class="tickets-header">
        <h2>Atendimento ao Cliente</h2>
        <button class="btn btn-primary" onclick="novoTicket()">Novo Ticket</button>
    </div>

    <div class="tickets-grid">
        <div class="tickets-filtros card">
            <h3>Filtros</h3>
            <form class="filtros-form">
                <div class="form-group">
                    <label>Status</label>
                    <div class="checkbox-grupo">
                        <label class="checkbox-item">
                            <input type="checkbox" name="status[]" value="aberto" checked>
                            <span>Aberto</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="status[]" value="andamento">
                            <span>Em Andamento</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="status[]" value="resolvido">
                            <span>Resolvido</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Prioridade</label>
                    <select name="prioridade" class="form-control">
                        <option value="">Todas</option>
                        <option value="alta">Alta</option>
                        <option value="media">Média</option>
                        <option value="baixa">Baixa</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-secondary">Aplicar Filtros</button>
            </form>
        </div>

        <div class="tickets-lista">
            <?php foreach ($tickets as $ticket): ?>
            <div class="ticket-card status-<?= $ticket['status'] ?>">
                <div class="ticket-header">
                    <span class="ticket-numero">#<?= $ticket['id'] ?></span>
                    <span class="ticket-data"><?= date('d/m/Y H:i', strtotime($ticket['data_criacao'])) ?></span>
                </div>
                
                <h4 class="ticket-titulo"><?= htmlspecialchars($ticket['titulo']) ?></h4>
                <div class="ticket-cliente"><?= htmlspecialchars($ticket['cliente_nome']) ?></div>
                
                <div class="ticket-footer">
                    <span class="prioridade prioridade-<?= $ticket['prioridade'] ?>">
                        <?= ucfirst($ticket['prioridade']) ?>
                    </span>
                    <button class="btn btn-sm" onclick="verTicket(<?= $ticket['id'] ?>)">
                        Ver Detalhes
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
