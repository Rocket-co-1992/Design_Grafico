// Funções para monitoramento em tempo real

function adicionarAlertaTabela(alerta) {
    const tabela = document.getElementById('tabela-alertas').getElementsByTagName('tbody')[0];
    const row = tabela.insertRow(0);
    row.id = `alerta-${alerta.id}`;
    row.className = `alerta-row ${alerta.criticidade}`;
    
    row.innerHTML = `
        <td>${formatarHora(alerta.data_geracao)}</td>
        <td>${alerta.tipo}</td>
        <td>${alerta.descricao}</td>
        <td>${capitalizar(alerta.nivel_escalonamento)}</td>
        <td>${alerta.status}</td>
        <td>
            <button class="btn btn-sm btn-primary" onclick="tratarAlerta(${alerta.id})">
                Tratar
            </button>
        </td>
    `;
    
    // Efeito de highlight na nova linha
    row.style.animation = 'highlightNew 2s';
}

function atualizarAlertaTabela(alerta) {
    const row = document.getElementById(`alerta-${alerta.id}`);
    if (row) {
        row.className = `alerta-row ${alerta.criticidade}`;
        row.cells[3].textContent = capitalizar(alerta.nivel_escalonamento);
        row.cells[4].textContent = alerta.status;
    }
}

function atualizarContadores() {
    fetch('api/alertas/contadores')
        .then(res => res.json())
        .then(data => {
            document.getElementById('total-alertas').textContent = data.total;
            document.getElementById('total-escalonados').textContent = data.escalonados;
        });
}

function tratarAlerta(id) {
    const modal = new bootstrap.Modal(document.getElementById('modalTratamento'));
    
    fetch(`api/alertas/${id}`)
        .then(res => res.json())
        .then(alerta => {
            preencherModalTratamento(alerta);
            modal.show();
        });
}

function preencherModalTratamento(alerta) {
    document.getElementById('alerta-tipo').textContent = alerta.tipo;
    document.getElementById('alerta-descricao').textContent = alerta.descricao;
    document.getElementById('form-tratamento').dataset.alertaId = alerta.id;
}

// Helpers
function formatarHora(data) {
    return new Date(data).toLocaleTimeString();
}

function capitalizar(texto) {
    return texto.charAt(0).toUpperCase() + texto.slice(1);
}

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    // Configura WebSocket
    const socket = io(SOCKET_URL);
    
    socket.on('connect', () => {
        document.getElementById('status-conexao').className = 'badge badge-success';
        document.getElementById('status-conexao').textContent = 'Conectado';
    });
    
    socket.on('disconnect', () => {
        document.getElementById('status-conexao').className = 'badge badge-danger';
        document.getElementById('status-conexao').textContent = 'Desconectado';
    });
    
    // Configura atualizações automáticas
    setInterval(atualizarContadores, 30000);
});
