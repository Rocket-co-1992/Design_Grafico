CREATE DATABASE IF NOT EXISTS erp_grafica;
USE erp_grafica;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nivel INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    endereco TEXT,
    cnpj VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    estoque INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    status VARCHAR(50) NOT NULL,
    valor_total DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE pedido_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    produto_id INT,
    quantidade INT NOT NULL,
    valor_unitario DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

CREATE TABLE configuracoes_preco (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT,
    quantidade_minima INT NOT NULL,
    desconto_percentual DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

CREATE TABLE contas_receber (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    valor DECIMAL(10,2) NOT NULL,
    data_vencimento DATE NOT NULL,
    data_pagamento DATE,
    status VARCHAR(20) DEFAULT 'pendente',
    forma_pagamento VARCHAR(50),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
);

CREATE TABLE contas_pagar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(200) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    data_vencimento DATE NOT NULL,
    data_pagamento DATE,
    status VARCHAR(20) DEFAULT 'pendente',
    categoria VARCHAR(50),
    fornecedor_id INT
);

CREATE TABLE fluxo_caixa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(20) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    data_movimento DATE NOT NULL,
    descricao TEXT,
    referencia_id INT,
    referencia_tipo VARCHAR(50)
);

CREATE TABLE ordem_producao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    status VARCHAR(50) DEFAULT 'pendente',
    data_inicio DATETIME,
    data_fim DATETIME,
    prioridade INT DEFAULT 0,
    observacoes TEXT,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
);

CREATE TABLE etapas_producao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ordem_id INT,
    nome VARCHAR(100),
    status VARCHAR(50),
    tempo_estimado INT,
    tempo_real INT,
    responsavel_id INT,
    data_inicio DATETIME,
    data_fim DATETIME,
    FOREIGN KEY (ordem_id) REFERENCES ordem_producao(id),
    FOREIGN KEY (responsavel_id) REFERENCES usuarios(id)
);

CREATE TABLE notificacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    titulo VARCHAR(100) NOT NULL,
    mensagem TEXT NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    lida BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE auditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    acao VARCHAR(50) NOT NULL,
    tabela VARCHAR(50) NOT NULL,
    registro_id INT,
    dados_anteriores TEXT,
    dados_novos TEXT,
    ip VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE permissoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE,
    descricao TEXT
);

CREATE TABLE usuario_permissoes (
    usuario_id INT,
    permissao_id INT,
    PRIMARY KEY (usuario_id, permissao_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (permissao_id) REFERENCES permissoes(id)
);

CREATE TABLE notificacoes_realtime (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    tipo VARCHAR(50) NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    mensagem TEXT,
    lida BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE categoria_produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    slug VARCHAR(100) UNIQUE,
    imagem VARCHAR(255)
);

CREATE TABLE produto_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT,
    nome VARCHAR(100),
    arquivo VARCHAR(255),
    thumbnail VARCHAR(255),
    dimensoes VARCHAR(50),
    formato VARCHAR(20),
    categoria VARCHAR(50),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

CREATE TABLE produto_opcoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT,
    nome VARCHAR(100),
    tipo ENUM('select', 'radio', 'checkbox'),
    obrigatorio BOOLEAN DEFAULT FALSE,
    ordem INT,
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

CREATE TABLE produto_opcoes_valores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    opcao_id INT,
    valor VARCHAR(100),
    preco_adicional DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (opcao_id) REFERENCES produto_opcoes(id)
);

CREATE TABLE designs_salvos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    produto_id INT,
    nome VARCHAR(100),
    dados_design TEXT,
    preview_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

CREATE TABLE pagamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    valor DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) NOT NULL,
    gateway VARCHAR(50),
    gateway_reference VARCHAR(100),
    data_pagamento DATETIME,
    tipo_pagamento VARCHAR(50),
    parcelas INT DEFAULT 1,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
);

CREATE TABLE endereco_entrega (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    cep VARCHAR(10),
    logradouro VARCHAR(100),
    numero VARCHAR(20),
    complemento VARCHAR(100),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(2),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
);

CREATE TABLE rastreamento_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    status VARCHAR(50) NOT NULL,
    descricao TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
);

CREATE TABLE avaliacoes_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    cliente_id INT,
    nota INT NOT NULL,
    comentario TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE programa_fidelidade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    pontos INT DEFAULT 0,
    nivel VARCHAR(50) DEFAULT 'bronze',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE historico_pontos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    pedido_id INT,
    pontos INT NOT NULL,
    tipo VARCHAR(50),
    descricao TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
);

CREATE TABLE recompensas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    pontos_necessarios INT NOT NULL,
    tipo ENUM('desconto', 'produto', 'servico') NOT NULL,
    valor DECIMAL(10,2),
    quantidade_disponivel INT,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE resgates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    recompensa_id INT,
    pontos_usados INT NOT NULL,
    status VARCHAR(50) DEFAULT 'pendente',
    data_resgate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_uso DATETIME,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (recompensa_id) REFERENCES recompensas(id)
);

CREATE TABLE cupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    tipo ENUM('valor', 'percentual') NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    data_inicio DATE,
    data_fim DATE,
    limite_usos INT,
    usos_realizados INT DEFAULT 0,
    valor_minimo DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE campanhas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    conteudo TEXT,
    segmento VARCHAR(50),
    status VARCHAR(20),
    data_inicio DATE,
    data_fim DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE campanhas_enviadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    campanha_id INT,
    cliente_id INT,
    tipo VARCHAR(50),
    status VARCHAR(20),
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (campanha_id) REFERENCES campanhas(id),
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE formatos_impressao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    largura DECIMAL(10,2) NOT NULL,
    altura DECIMAL(10,2) NOT NULL,
    sangria DECIMAL(10,2) DEFAULT 0.0,
    unidade ENUM('mm', 'cm', 'pol') DEFAULT 'mm'
);

CREATE TABLE materiais_impressao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo VARCHAR(50),
    gramatura INT,
    preco_m2 DECIMAL(10,2),
    formato_id INT,
    disponivel BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (formato_id) REFERENCES formatos_impressao(id)
);

CREATE TABLE acabamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo VARCHAR(50),
    preco_base DECIMAL(10,2),
    preco_unitario DECIMAL(10,2),
    tempo_producao INT,
    descricao TEXT
);

CREATE TABLE custos_impressao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipamento VARCHAR(100),
    custo_hora DECIMAL(10,2),
    custo_setup DECIMAL(10,2),
    velocidade_impressao INT,
    desperdicio_padrao DECIMAL(5,2)
);

CREATE TABLE equipamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    modelo VARCHAR(100),
    tipo VARCHAR(50),
    status VARCHAR(50) DEFAULT 'ativo',
    ultima_manutencao DATE,
    proxima_manutencao DATE,
    contador_impressoes BIGINT DEFAULT 0,
    observacoes TEXT
);

CREATE TABLE manutencoes_equipamento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipamento_id INT,
    tipo VARCHAR(50),
    descricao TEXT,
    custo DECIMAL(10,2),
    data_realizada DATE,
    responsavel_id INT,
    FOREIGN KEY (equipamento_id) REFERENCES equipamentos(id),
    FOREIGN KEY (responsavel_id) REFERENCES usuarios(id)
);

CREATE TABLE checklist_qualidade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo_produto VARCHAR(50),
    obrigatorio BOOLEAN DEFAULT TRUE
);

CREATE TABLE itens_checklist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    checklist_id INT,
    descricao TEXT,
    tipo_verificacao VARCHAR(50),
    ordem INT,
    FOREIGN KEY (checklist_id) REFERENCES checklist_qualidade(id)
);

CREATE TABLE inspecoes_qualidade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ordem_producao_id INT,
    checklist_id INT,
    inspetor_id INT,
    status VARCHAR(50),
    observacoes TEXT,
    data_inspecao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ordem_producao_id) REFERENCES ordem_producao(id),
    FOREIGN KEY (checklist_id) REFERENCES checklist_qualidade(id),
    FOREIGN KEY (inspetor_id) REFERENCES usuarios(id)
);

CREATE TABLE resultados_inspecao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    inspecao_id INT,
    item_checklist_id INT,
    conforme BOOLEAN,
    observacao TEXT,
    FOREIGN KEY (inspecao_id) REFERENCES inspecoes_qualidade(id),
    FOREIGN KEY (item_checklist_id) REFERENCES itens_checklist(id)
);

CREATE TABLE metas_qualidade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    indicador VARCHAR(100) NOT NULL,
    meta DECIMAL(10,2) NOT NULL,
    periodo VARCHAR(20),
    data_inicio DATE,
    data_fim DATE,
    observacoes TEXT
);

CREATE TABLE custos_qualidade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('prevencao', 'avaliacao', 'falha_interna', 'falha_externa') NOT NULL,
    descricao TEXT,
    valor DECIMAL(10,2) NOT NULL,
    data_ocorrencia DATE,
    ordem_producao_id INT,
    usuario_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ordem_producao_id) REFERENCES ordem_producao(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE analise_falhas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_falha VARCHAR(100),
    causa_raiz TEXT,
    acao_corretiva TEXT,
    status VARCHAR(50),
    responsavel_id INT,
    prazo_conclusao DATE,
    data_conclusao DATE,
    custo_id INT,
    FOREIGN KEY (responsavel_id) REFERENCES usuarios(id),
    FOREIGN KEY (custo_id) REFERENCES custos_qualidade(id)
);

CREATE TABLE kanban_colunas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    ordem INT NOT NULL,
    cor VARCHAR(7),
    limite_cartoes INT
);

CREATE TABLE kanban_cartoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    coluna_id INT,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    prioridade ENUM('baixa', 'media', 'alta', 'urgente') DEFAULT 'media',
    responsavel_id INT,
    prazo DATE,
    posicao INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (coluna_id) REFERENCES kanban_colunas(id),
    FOREIGN KEY (responsavel_id) REFERENCES usuarios(id)
);

CREATE TABLE kanban_etiquetas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    cor VARCHAR(7) NOT NULL
);

CREATE TABLE kanban_cartao_etiquetas (
    cartao_id INT,
    etiqueta_id INT,
    PRIMARY KEY (cartao_id, etiqueta_id),
    FOREIGN KEY (cartao_id) REFERENCES kanban_cartoes(id),
    FOREIGN KEY (etiqueta_id) REFERENCES kanban_etiquetas(id)
);

CREATE TABLE kanban_comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cartao_id INT,
    usuario_id INT,
    comentario TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cartao_id) REFERENCES kanban_cartoes(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE kanban_checklist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cartao_id INT,
    item VARCHAR(255) NOT NULL,
    concluido BOOLEAN DEFAULT FALSE,
    ordem INT NOT NULL,
    FOREIGN KEY (cartao_id) REFERENCES kanban_cartoes(id)
);
