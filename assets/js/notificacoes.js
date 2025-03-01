class NotificacaoManager {
    constructor() {
        this.interval = null;
        this.notificacoesContainer = document.getElementById('notificacoes');
        this.contador = document.getElementById('notificacao-contador');
        this.init();
    }
    
    init() {
        this.verificarNotificacoes();
        this.interval = setInterval(() => this.verificarNotificacoes(), 30000); // 30 segundos
    }
    
    async verificarNotificacoes() {
        try {
            const response = await fetch('/api/notificacoes/novas');
            const notificacoes = await response.json();
            
            if (notificacoes.length > 0) {
                this.atualizarContador(notificacoes.length);
                this.exibirNotificacoes(notificacoes);
                this.notificarUsuario(notificacoes[0]);
            }
        } catch (error) {
            console.error('Erro ao buscar notificações:', error);
        }
    }
    
    atualizarContador(quantidade) {
        this.contador.textContent = quantidade;
        this.contador.style.display = quantidade > 0 ? 'block' : 'none';
    }
    
    exibirNotificacoes(notificacoes) {
        this.notificacoesContainer.innerHTML = notificacoes.map(n => `
            <div class="notificacao-item" data-id="${n.id}">
                <h4>${n.titulo}</h4>
                <p>${n.mensagem}</p>
                <small>${new Date(n.created_at).toLocaleString()}</small>
            </div>
        `).join('');
    }
    
    notificarUsuario(notificacao) {
        if (Notification.permission === "granted") {
            new Notification(notificacao.titulo, {
                body: notificacao.mensagem,
                icon: '/assets/img/icon.png'
            });
        }
    }
    
    async marcarComoLida(id) {
        try {
            await fetch(`/api/notificacoes/${id}/lida`, { method: 'POST' });
            document.querySelector(`[data-id="${id}"]`).remove();
        } catch (error) {
            console.error('Erro ao marcar notificação como lida:', error);
        }
    }
}

// Funções para gerenciamento de notificações
async function marcarLida(id) {
    try {
        const response = await fetch(`/api/notificacoes/${id}/lida`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' }
        });

        if (response.ok) {
            const notif = document.querySelector(`[data-notif-id="${id}"]`);
            notif.classList.add('lida');
            atualizarContador();
        }
    } catch (error) {
        showAlert('Erro ao marcar notificação como lida', 'danger');
    }
}

async function marcarTodasLidas() {
    try {
        const response = await fetch('/api/notificacoes/marcar-todas', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' }
        });

        if (response.ok) {
            document.querySelectorAll('.notif-item:not(.lida)').forEach(notif => {
                notif.classList.add('lida');
            });
            atualizarContador(0);
        }
    } catch (error) {
        showAlert('Erro ao marcar notificações como lidas', 'danger');
    }
}

function filtrarNotificacoes(tipo) {
    const notificacoes = document.querySelectorAll('.notif-item');
    notificacoes.forEach(notif => {
        if (!tipo || notif.dataset.tipo === tipo) {
            notif.style.display = 'flex';
        } else {
            notif.style.display = 'none';
        }
    });
}

function atualizarContador(count) {
    const contador = document.querySelector('.notif-contador');
    if (contador) {
        contador.textContent = count || '0';
        contador.style.display = count > 0 ? 'block' : 'none';
    }
}

// Verificar novas notificações periodicamente
setInterval(async () => {
    try {
        const response = await fetch('/api/notificacoes/novas');
        const data = await response.json();
        
        if (data.count > 0) {
            atualizarContador(data.count);
            if (data.notificacoes) {
                adicionarNovasNotificacoes(data.notificacoes);
            }
        }
    } catch (error) {
        console.error('Erro ao verificar notificações:', error);
    }
}, 30000); // Verificar a cada 30 segundos

// Inicializar quando o documento estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    new NotificacaoManager();
    
    // Solicitar permissão para notificações
    if (Notification.permission !== "granted") {
        Notification.requestPermission();
    }
});
