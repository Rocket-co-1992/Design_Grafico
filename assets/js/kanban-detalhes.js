let socket;

document.addEventListener('DOMContentLoaded', function() {
    // Inicializa socket para atualizações em tempo real
    socket = io(SOCKET_URL);
    
    socket.on('cartao_atualizado', function(data) {
        if (data.cartao_id === currentCardId) {
            atualizarDetalhesCartao(data);
        }
    });
});

function atualizarResponsavel(userId, cardId) {
    fetch('api/kanban/atualizar-responsavel', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            cartao_id: cardId,
            usuario_id: userId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            notificar('Responsável atualizado com sucesso');
        }
    });
}

function atualizarPrazo(data, cardId) {
    fetch('api/kanban/atualizar-prazo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            cartao_id: cardId,
            prazo: data
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            notificar('Prazo atualizado com sucesso');
        }
    });
}

function adicionarComentario(event, cardId) {
    event.preventDefault();
    const input = event.target.querySelector('input');
    const comentario = input.value.trim();
    
    if (comentario) {
        fetch('api/kanban/adicionar-comentario', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                cartao_id: cardId,
                comentario: comentario
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                input.value = '';
                atualizarComentarios(data.comentarios);
            }
        });
    }
}

function atualizarDetalhesCartao(data) {
    // Atualiza checklist
    if (data.checklist) {
        atualizarChecklist(data.checklist);
    }
    
    // Atualiza comentários
    if (data.comentarios) {
        atualizarComentarios(data.comentarios);
    }
    
    // Atualiza informações gerais
    if (data.info) {
        document.querySelector('.modal-title').innerHTML = `
            <span class="badge badge-${data.info.prioridade}">
                ${data.info.prioridade}
            </span>
            ${data.info.titulo}
        `;
    }
}

function notificar(mensagem, tipo = 'success') {
    const toast = new bootstrap.Toast(document.createElement('div'));
    toast._element.className = `toast bg-${tipo} text-white`;
    toast._element.innerHTML = mensagem;
    document.body.appendChild(toast._element);
    toast.show();
    
    setTimeout(() => {
        toast._element.remove();
    }, 3000);
}
