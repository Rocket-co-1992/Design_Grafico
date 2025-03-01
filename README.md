# Sistema de Gestão para Gráfica Digital

Sistema de gestão completo para gráficas digitais, incluindo orçamentação, gestão de produção, controle de qualidade e fidelização de clientes.

## Estrutura do Projeto

```
/workspaces/Design_Grafico/
├── api/
│   ├── endpoints/
│   │   ├── ClientesEndpoint.php
│   │   ├── PedidosEndpoint.php
│   │   └── ...
│   └── index.php
├── assets/
│   ├── css/
│   │   ├── arquivos/
│   │   │   └── galeria.css
│   │   ├── cadastros/
│   │   │   └── produtos.css
│   │   ├── configuracoes/
│   │   │   └── sistema.css
│   │   ├── custos/
│   │   │   └── precificacao.css
│   │   ├── equipamentos/
│   │   │   └── status.css
│   │   ├── financeiro/
│   │   │   └── fluxo.css
│   │   ├── materiais/
│   │   │   └── estoque.css
│   │   ├── notificacoes.css
│   │   ├── orcamentos/
│   │   │   └── avancado.css
│   │   ├── pedidos/
│   │   │   └── lista.css
│   │   ├── producao/
│   │   │   ├── calendario.css
│   │   │   ├── ordem-servico.css
│   │   │   └── painel.css
│   │   ├── qualidade/
│   │   │   └── inspecao.css
│   │   ├── relatorios/
│   │   │   ├── analise.css
│   │   │   └── dashboard.css
│   │   └── style.css
│   ├── js/
│   │   ├── configuracoes/
│   │   ├── notificacoes/
│   │   ├── relatorios/
│   │   └── main.js
│   └── img/
├── config/
│   └── config.php
├── core/
│   ├── Auth.php
│   ├── Database.php
│   └── RateLimit.php
├── sql/
│   └── database.sql
└── templates/
    ├── admin/
    │   └── usuarios.php
    ├── arquivos/
    │   └── galeria.php
    ├── cadastros/
    │   └── produtos.php
    ├── configuracoes/
    │   └── sistema.php
    ├── custos/
    │   └── precificacao.php
    ├── equipamentos/
    │   └── status.php
    ├── estoque/
    │   └── materiais.php
    ├── financeiro/
    │   ├── dashboard.php
    │   └── fluxo-caixa.php
    ├── fidelidade/
    │   └── pontos.php
    ├── notificacoes/
    │   └── centro.php
    ├── orcamentos/
    │   ├── form-avancado.php
    │   └── novo.php
    ├── pedidos/
    │   └── lista.php
    ├── producao/
    │   ├── calendario.php
    │   ├── ordem-servico.php
    │   └── painel.php
    ├── qualidade/
    │   └── inspecao.php
    └── relatorios/
        ├── analise.php
        └── dashboard.php
```

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

## Requisitos Técnicos

### Servidor
- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx
- 2GB RAM mínimo
- 20GB espaço em disco

### Extensões PHP Requeridas
- pdo
- pdo_mysql
- gd
- mbstring
- xml
- zip
- curl
- json

### Navegadores Suportados
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Instalação

1. Clone o repositório
```bash
git clone https://github.com/seu-usuario/Design_Grafico.git
```

2. Configure o banco de dados
```bash
mysql -u root -p < sql/database.sql
```

3. Configure as permissões
```bash
chmod -R 755 .
chmod -R 777 storage/
```

4. Configure o arquivo de ambiente
```bash
cp config/config.example.php config/config.php
```

5. Instale as dependências
```bash
composer install
```

## Segurança

- Rate limiting implementado
- Proteção contra CSRF
- Sanitização de inputs
- Prepared Statements
- Controle de sessão
- Logs de auditoria

## Changelog

### [1.1.0] - 2024-02-01
- Adicionado módulo de gestão de arquivos
- Melhorias no controle de qualidade
- Nova interface de relatórios
- Correções de bugs

### [1.0.0] - 2024-01-20
- Lançamento inicial
- Módulo de orçamentação
- Sistema de qualidade
- Gestão de produção
- Programa de fidelidade