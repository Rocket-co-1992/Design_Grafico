# Sistema de Gestão para Gráfica Digital

Sistema de gestão completo para gráficas digitais, incluindo orçamentação, gestão de produção, controle de qualidade e fidelização de clientes.

## Estrutura do Projeto

```
/Design_Grafico/
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
├── config/
│   └── config.php
├── core/
│   ├── Auth.php
│   ├── Backup.php
│   └── Logger.php
├── models/
│   ├── Configuracao.php
│   └── OrcamentoGrafico.php
├── views/
│   └── configuracoes/
│       └── sistema.php
├── sql/
│   └── database.sql
└── .github/
    └── workflows/
        └── php.yml
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
- Bootstrap 5

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

4. Instale as dependências
```bash
composer install
```

## Contribuição

1. Fork o projeto
2. Crie sua Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a Branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE.md](LICENSE.md) para detalhes.

## Suporte

Para suporte, envie um email para suporte@grafica.com ou abra uma issue no GitHub.

## Changelog

### [1.0.0] - 2024-01-20
- Lançamento inicial
- Módulo de orçamentação
- Sistema de qualidade
- Gestão de produção
- Programa de fidelidade