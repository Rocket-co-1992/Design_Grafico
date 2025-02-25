document.addEventListener('DOMContentLoaded', function() {
    // Inicializa drag and drop com Dragula
    var drake = dragula(document.querySelectorAll('.cards-container'), {
        moves: function(el, container, handle) {
            return handle.className !== 'card-content';
        }
    });
    
    // Evento quando um cartão é solto em uma nova posição
    drake.on('drop', function(el, target, source, sibling) {
        const cartaoId = el.dataset.id;
        const colunaId = target.parentElement.dataset.id;
        const posicao = Array.from(target.children).indexOf(el) + 1;
        
        atualizarPosicaoCartao(cartaoId, colunaId, posicao);
    });
    
    // Atualiza posição no servidor
    function atualizarPosicaoCartao(cartaoId, colunaId, posicao) {
        fetch('api/kanban/mover-cartao', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                cartao_id: cartaoId,
                coluna_id: colunaId,
                posicao: posicao
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Erro ao mover cartão:', data.message);
                // Recarrega o quadro em caso de erro
                location.reload();
            }
        });
    }
});

// Funções de interação
function abrirDetalhesCartao(id) {
    fetch(`api/kanban/cartao/${id}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('cardDetailsModal').innerHTML = html;
            new bootstrap.Modal(document.getElementById('cardDetailsModal')).show();
        });
}

function filtrarCards() {
    const modal = new bootstrap.Modal(document.getElementById('filterModal'));
    modal.show();
}

function abrirConfiguracoesQuadro() {
    const modal = new bootstrap.Modal(document.getElementById('configModal'));
    modal.show();
}

// Atualiza checklist
function marcarItem(checkboxEl, itemId) {
    fetch('api/kanban/checklist/atualizar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            item_id: itemId,
            concluido: checkboxEl.checked
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            atualizarProgressoChecklist(checkboxEl.closest('.card-details'));
        }
    });
}

function atualizarProgressoChecklist(cardEl) {
    const total = cardEl.querySelectorAll('.checklist-item').length;
    const concluidos = cardEl.querySelectorAll('.checklist-item:checked').length;
    const progresso = (concluidos / total) * 100;
    
    cardEl.querySelector('.progress-bar').style.width = progresso + '%';
}
