# Sistema de Gestão para Gráfica Digital

Sistema de gestão completo para gráficas digitais, incluindo orçamentação, gestão de produção, controle de qualidade e fidelização de clientes.
## Componentes Principais

### 1. Módulo de Orçamentação
- Cálculo automático de custos
- Gestão de materiais e acabamentos
- Precificação dinâmica

### 2. Gestão de Produção
- Kanban digital
- Controle de equipamentos
- Monitoramento de produção

### 3. Controle de Qualidade
- Checklists personalizáveis
- Inspeções de qualidade
- Análise de falhas

### 4. Fidelização de Clientes
- Programa de pontos
- Sistema de recompensas
- Gestão de campanhas

## Banco de Dados

### Principais Tabelas
- usuarios
- clientes
- produtos
- pedidos
- ordem_producao
- configuracoes
- programa_fidelidade

### Features de Qualidade
- Controle de equipamentos
- Manutenções preventivas
- Gestão de qualidade

## Tecnologias Utilizadas

- PHP 7.4+
- MySQL 5.7+
- HTML5/CSS3
- JavaScript
- CSS puro

## Requisitos do Sistema

- Servidor Web Apache/Nginx
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Extensões PHP:
  - PDO
  - MySQLi
  - GD
  - ZIP

## Instalação

1. Clone o repositório
```bash
git clone https://github.com/seu-usuario/Design_Grafico.git
```

2. Configure o banco de dados
```bash
mysql -u root -p < sql/database.sql
```

3. Configure o arquivo de ambiente
```bash
cp config/config.example.php config/config.php
```

## Changelog

### [1.0.0] - 2024-01-20
- Lançamento inicial
- Módulo de orçamentação
- Sistema de qualidade
- Gestão de produção
- Programa de fidelidade