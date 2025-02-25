let filtrosAtivos = {};

function aplicarFiltros(event) {
    event.preventDefault();
    const form = document.getElementById('formFiltros');
    const formData = new FormData(form);
    
    // Constrói objeto de filtros
    filtrosAtivos = {
        busca: formData.get('busca'),
        prioridades: formData.getAll('prioridades'),
        responsavel: formData.get('responsavel'),
        etiquetas: formData.getAll('etiquetas'),
        prazo: formData.get('prazo')
    };
    
    // Aplica filtros visualmente
    filtrarCartoes();
    
    // Fecha modal
    bootstrap.Modal.getInstance(document.getElementById('filterModal')).hide();
    
    // Atualiza contador de filtros ativos
    atualizarContadorFiltros();
}

function filtrarCartoes() {
    const cartoes = document.querySelectorAll('.kanban-card');
    
    cartoes.forEach(cartao => {
        const visivel = verificarCartao(cartao);
        cartao.style.display = visivel ? 'block' : 'none';
    });
}

function verificarCartao(cartao) {
    // Verifica busca
    if (filtrosAtivos.busca) {
        const texto = cartao.textContent.toLowerCase();
        if (!texto.includes(filtrosAtivos.busca.toLowerCase())) {
            return false;
        }
    }
    
    // Verifica prioridade
    if (filtrosAtivos.prioridades.length > 0) {
        const prioridade = cartao.dataset.prioridade;
        if (!filtrosAtivos.prioridades.includes(prioridade)) {
            return false;
        }
    }
    
    // Verifica responsável
    if (filtrosAtivos.responsavel) {
        const responsavel = cartao.dataset.responsavel;
        if (filtrosAtivos.responsavel === 'sem' && responsavel) {
            return false;
        }
        if (filtrosAtivos.responsavel !== 'sem' && responsavel !== filtrosAtivos.responsavel) {
            return false;
        }
    }
    
    // Verifica etiquetas
    if (filtrosAtivos.etiquetas.length > 0) {
        const etiquetasCartao = cartao.dataset.etiquetas.split(',');
        const temEtiqueta = filtrosAtivos.etiquetas.some(e => etiquetasCartao.includes(e));
        if (!temEtiqueta) {
            return false;
        }
    }
    
    // Verifica prazo
    if (filtrosAtivos.prazo) {
        const prazo = new Date(cartao.dataset.prazo);
        const hoje = new Date();
        
        switch (filtrosAtivos.prazo) {
            case 'atrasado':
                if (prazo >= hoje) return false;
                break;
            case 'hoje':
                if (!mesmodia(prazo, hoje)) return false;
                break;
            case 'semana':
                if (!mesmaSemana(prazo, hoje)) return false;
                break;
            case 'sem':
                if (cartao.dataset.prazo) return false;
                break;
        }
    }
    
    return true;
}

function limparFiltros() {
    document.getElementById('formFiltros').reset();
    filtrosAtivos = {};
    filtrarCartoes();
    atualizarContadorFiltros();
}

function atualizarContadorFiltros() {
    const contador = document.getElementById('filtrosAtivos');
    const total = Object.values(filtrosAtivos).flat().filter(Boolean).length;
    
    contador.textContent = total || '';
    contador.style.display = total ? 'inline' : 'none';
}

// Funções auxiliares
function mesmodia(d1, d2) {
    return d1.getDate() === d2.getDate() &&
           d1.getMonth() === d2.getMonth() &&
           d1.getFullYear() === d2.getFullYear();
}

function mesmaSemana(d1, d2) {
    const oneDay = 24 * 60 * 60 * 1000;
    const diffDays = Math.round(Math.abs((d1 - d2) / oneDay));
    return diffDays <= 7;
}
