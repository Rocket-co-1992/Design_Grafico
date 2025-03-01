<div class="calendario-container">
    <div class="calendario-header">
        <div class="mes-navegacao">
            <button class="btn btn-outline" onclick="mesAnterior()"><i class="icon-arrow-left"></i></button>
            <h2 id="mesAnoAtual"><?= $mes_atual ?></h2>
            <button class="btn btn-outline" onclick="mesSeguinte()"><i class="icon-arrow-right"></i></button>
        </div>
        <div class="visualizacao-toggle">
            <button class="btn btn-outline active" data-view="mes">Mês</button>
            <button class="btn btn-outline" data-view="semana">Semana</button>
            <button class="btn btn-outline" data-view="dia">Dia</button>
        </div>
    </div>

    <div class="calendario-grid">
        <div class="dias-semana">
            <div>Dom</div>
            <div>Seg</div>
            <div>Ter</div>
            <div>Qua</div>
            <div>Qui</div>
            <div>Sex</div>
            <div>Sáb</div>
        </div>
        
        <div class="dias-grid">
            <?php foreach ($calendario as $dia): ?>
            <div class="dia-cell <?= $dia['classe'] ?>" data-date="<?= $dia['data'] ?>">
                <div class="dia-header">
                    <span class="dia-numero"><?= $dia['dia'] ?></span>
                    <?php if ($dia['atual']): ?>
                        <span class="badge badge-primary">Hoje</span>
                    <?php endif; ?>
                </div>

                <div class="dia-conteudo">
                    <?php foreach ($dia['eventos'] as $evento): ?>
                    <div class="evento-item prioridade-<?= $evento['prioridade'] ?>"
                         onclick="verEvento(<?= $evento['id'] ?>)">
                        <span class="evento-hora"><?= $evento['hora'] ?></span>
                        <span class="evento-titulo"><?= htmlspecialchars($evento['titulo']) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if (count($dia['eventos']) > 3): ?>
                <div class="mais-eventos">
                    +<?= count($dia['eventos']) - 3 ?> eventos
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="agenda-lateral">
        <div class="agenda-header">
            <h3>Próximos Eventos</h3>
            <button class="btn btn-primary" onclick="novoEvento()">+ Evento</button>
        </div>

        <div class="eventos-lista">
            <?php foreach ($proximos_eventos as $evento): ?>
            <div class="evento-card">
                <div class="evento-meta">
                    <span class="data"><?= date('d/m/Y', strtotime($evento['data'])) ?></span>
                    <span class="hora"><?= date('H:i', strtotime($evento['hora'])) ?></span>
                </div>
                <h4 class="evento-titulo"><?= htmlspecialchars($evento['titulo']) ?></h4>
                <div class="evento-info">
                    <span class="tipo-<?= $evento['tipo'] ?>"><?= ucfirst($evento['tipo']) ?></span>
                    <?php if ($evento['responsavel']): ?>
                        <span class="responsavel">
                            <img src="<?= $evento['responsavel_avatar'] ?>" alt="Avatar" class="avatar-mini">
                            <?= htmlspecialchars($evento['responsavel_nome']) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
