document.addEventListener('DOMContentLoaded', () => {
    initCharts();
    setupEventListeners();
});

function initCharts() {
    const ctx = document.getElementById('graficoTemporal').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Métrica Principal',
                data: [],
                borderColor: 'rgb(52, 152, 219)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    window.analiseChart = chart;
}

function setupEventListeners() {
    const form = document.getElementById('formFiltros');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        await atualizarAnalise(Object.fromEntries(formData));
    });
}

async function atualizarAnalise(filtros) {
    try {
        const response = await fetch('/api/analise', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(filtros)
        });

        if (!response.ok) throw new Error('Erro ao carregar dados');

        const data = await response.json();
        atualizarGraficos(data.graficos);
        atualizarTabela(data.tabela);
        atualizarMetricas(data.metricas);

    } catch (error) {
        console.error('Erro:', error);
        showAlert('Erro ao atualizar análise', 'danger');
    }
}

function atualizarGraficos(dados) {
    const chart = window.analiseChart;
    chart.data.labels = dados.labels;
    chart.data.datasets[0].data = dados.values;
    chart.update();
}

function atualizarTabela(dados) {
    const tbody = document.querySelector('.tabela-analise tbody');
    tbody.innerHTML = dados.map(linha => `
        <tr>
            <td>${linha.periodo}</td>
            ${Object.entries(linha.valores).map(([key, value]) => `
                <td class="${value.class || ''}">${value.valor}</td>
            `).join('')}
        </tr>
    `).join('');
}

function exportarGrafico() {
    const canvas = document.getElementById('graficoTemporal');
    const link = document.createElement('a');
    link.download = 'analise-grafico.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}
