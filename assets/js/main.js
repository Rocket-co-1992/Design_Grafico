// Funções utilitárias
const $ = selector => document.querySelector(selector);
const $$ = selector => document.querySelectorAll(selector);

// Função para mostrar alertas
function showAlert(message, type = 'info') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    
    $('.content').insertAdjacentElement('afterbegin', alert);
    
    setTimeout(() => alert.remove(), 5000);
}

// Handler para formulários AJAX
function handleForm(form, callback) {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        
        try {
            const response = await fetch(form.action, {
                method: form.method,
                body: formData
            });
            
            const data = await response.json();
            
            if (response.ok) {
                callback?.(data);
            } else {
                showAlert(data.erro || 'Erro ao processar requisição', 'danger');
            }
        } catch (error) {
            showAlert('Erro de conexão', 'danger');
        }
    });
}

// Inicialização
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar dropdowns
    $$('.dropdown').forEach(dropdown => {
        const trigger = dropdown.querySelector('.dropdown-trigger');
        if (trigger) {
            trigger.addEventListener('click', () => {
                dropdown.classList.toggle('active');
            });
        }
    });
    
    // Fechar dropdowns ao clicar fora
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.dropdown')) {
            $$('.dropdown.active').forEach(d => d.classList.remove('active'));
        }
    });
});
