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

// Inicializar quando o documento estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    new NotificacaoManager();
    
    // Solicitar permissão para notificações
    if (Notification.permission !== "granted") {
        Notification.requestPermission();
    }
});
