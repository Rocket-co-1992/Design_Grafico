document.addEventListener('DOMContentLoaded', () => {
    setupFileUploads();
    setupFormValidation();
});

function setupFileUploads() {
    const uploadAreas = document.querySelectorAll('.upload-area');
    uploadAreas.forEach(area => {
        const input = area.querySelector('.file-input');
        const preview = area.querySelector('.preview-img');

        area.addEventListener('dragover', (e) => {
            e.preventDefault();
            area.classList.add('dragover');
        });

        area.addEventListener('dragleave', () => {
            area.classList.remove('dragover');
        });

        area.addEventListener('drop', (e) => {
            e.preventDefault();
            area.classList.remove('dragover');
            handleFile(e.dataTransfer.files[0], preview);
        });

        input.addEventListener('change', (e) => {
            handleFile(e.target.files[0], preview);
        });
    });
}

function handleFile(file, preview) {
    if (!file || !file.type.startsWith('image/')) {
        showAlert('Arquivo inválido. Por favor, selecione uma imagem.', 'danger');
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        preview.src = e.target.result;
        preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
}

async function salvarConfiguracoes() {
    const forms = document.querySelectorAll('.config-form');
    const formData = new FormData();

    forms.forEach(form => {
        const data = new FormData(form);
        for (let [key, value] of data.entries()) {
            formData.append(key, value);
        }
    });

    try {
        const response = await fetch('/api/configuracoes', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        
        if (response.ok) {
            showAlert('Configurações salvas com sucesso!', 'success');
        } else {
            throw new Error(data.erro || 'Erro ao salvar configurações');
        }
    } catch (error) {
        showAlert(error.message, 'danger');
    }
}

function toggleIntegracao(id) {
    const config = document.querySelector(`.integracao-config[data-id="${id}"]`);
    config.style.display = config.style.display === 'none' ? 'block' : 'none';
}

function toggleSenha(btn) {
    const input = btn.previousElementSibling;
    const icon = btn.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('icon-eye', 'icon-eye-off');
    } else {
        input.type = 'password';
        icon.classList.replace('icon-eye-off', 'icon-eye');
    }
}

function exportarConfiguracoes() {
    const forms = document.querySelectorAll('.config-form');
    const config = {};

    forms.forEach(form => {
        const formData = new FormData(form);
        for (let [key, value] of formData.entries()) {
            config[key] = value;
        }
    });

    const blob = new Blob([JSON.stringify(config, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'configuracoes.json';
    a.click();
    URL.revokeObjectURL(url);
}
