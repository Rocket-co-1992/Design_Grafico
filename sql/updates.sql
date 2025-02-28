
-- Add new indexes to improve performance
ALTER TABLE pedidos ADD INDEX idx_cliente_data (cliente_id, created_at);
ALTER TABLE historico_pontos ADD INDEX idx_cliente_tipo (cliente_id, tipo);
ALTER TABLE resgates ADD INDEX idx_cliente_data (cliente_id, data_resgate);

-- Add new columns for enhanced features
ALTER TABLE recompensas
ADD COLUMN imagem VARCHAR(255) AFTER descricao,
ADD COLUMN prioridade INT DEFAULT 0 AFTER imagem,
ADD COLUMN validade DATE AFTER prioridade;

-- New table for reward categories
CREATE TABLE categorias_recompensa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    icone VARCHAR(50),
    ordem INT DEFAULT 0,
    ativo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add category relation to rewards
ALTER TABLE recompensas
ADD COLUMN categoria_id INT AFTER id,
ADD FOREIGN KEY (categoria_id) REFERENCES categorias_recompensa(id);

-- New table for reward rules
CREATE TABLE regras_recompensa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recompensa_id INT NOT NULL,
    tipo ENUM('minimo_compras', 'dias_membro', 'nivel_minimo') NOT NULL,
    valor VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recompensa_id) REFERENCES recompensas(id)
);

-- New table for temporary reward locks
CREATE TABLE bloqueios_recompensa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    recompensa_id INT NOT NULL,
    motivo TEXT,
    data_inicio DATETIME NOT NULL,
    data_fim DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (recompensa_id) REFERENCES recompensas(id)
);
