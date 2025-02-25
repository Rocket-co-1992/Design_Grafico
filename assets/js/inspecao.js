document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formInspecao');
    
    // Valida formulário antes de enviar
    form.addEventListener('submit', function(e) {
        let valid = true;
        const items = form.querySelectorAll('input[type="radio"]');
        const groups = {};
        
        // Agrupa radio buttons
        items.forEach(item => {
            const name = item.getAttribute('name');
            if (!groups[name]) {
                groups[name] = [];
            }
            groups[name].push(item);
        });
        
        // Verifica se todos os grupos têm uma opção selecionada
        Object.values(groups).forEach(group => {
            if (!group.some(radio => radio.checked)) {
                valid = false;
            }
        });
        
        if (!valid) {
            e.preventDefault();
            alert('Por favor, preencha todos os itens da inspeção.');
        }
    });
    
    // Auto-expande textareas
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });
});
