# W5i Help Desk

Sistema de gerenciamento de chamados de suporte técnico desenvolvido para o processo seletivo da **W5i Tecnologia**.

---

## Sobre o projeto

O W5i Help Desk é uma aplicação web para abertura e acompanhamento de chamados de suporte técnico. O sistema permite que chamados sejam abertos, atribuídos a setores, classificados por nível de urgência e acompanhados do início ao fim do atendimento — com registro de tempo e solução aplicada.

---

## Tecnologias utilizadas

| Tecnologia | Decisão |
|---|---|
| PHP 8 puro | Arquitetura MVC sem framework — foco em fundamentos |
| MySQL / MariaDB | Banco relacional com foreign keys e prepared statements |
| Tailwind CSS (CDN) | Estilização ágil — em produção substituir por CLI build |
| XAMPP | Ambiente de desenvolvimento local |

---

## Funcionalidades

### Chamados
- Abertura de chamados com título, descrição, setor e urgência
- Listagem completa com cards de resumo por status
- Check-in — inicia o atendimento com registro de horário
- Check-out — finaliza com registro de solução aplicada
- Cancelamento de chamados abertos
- Exclusão de chamados finalizados ou cancelados
- Destaque visual e badge de atraso para chamados fora do prazo
- Cálculo de tempo decorrido com fuso horário America/Sao_Paulo

### Setores
- Cadastro de setores com validação de nome duplicado
- Listagem com avatar de iniciais
- Remoção com proteção contra exclusão de setor vinculado a chamados

### Prioridades
- 4 níveis fixos de urgência — o sistema define os prazos, não o usuário
- Cards informativos com descrição de cada nível
- Prazos automáticos por nível

### Interface
- Toast notifications globais com limpeza de URL após exibição
- Modal de confirmação elegante para ações destrutivas
- Layout responsivo — tabela no desktop, cards no mobile
- Menu hambúrguer no mobile

---

## Níveis de urgência

| Nível | Prazo | Descrição |
|---|---|---|
| 🔴 Crítico | 1h até 3h | Sistema fora do ar, perda de dados ou impacto crítico |
| 🟠 Alta | 4h até 7h | Funcionalidade importante comprometida |
| 🟡 Médio | 8h até 20h | Problema com solução de contorno disponível |
| 🟢 Baixo | A partir de 24h | Dúvidas, melhorias ou sem impacto operacional |

---

## Fluxo de um chamado
[Cliente abre chamado]
↓
[ Aberto ]
↓
[Técnico faz check-in]
↓
[ Em atendimento ] ──── [Cliente cancela] ──→ [ Cancelado ]
↓
[Técnico faz check-out + solução]
↓
[ Finalizado ]

---

## Arquitetura

O projeto segue o padrão **MVC (Model-View-Controller)** implementado em PHP puro:
w5i-helpdesk/
├── app/
│   ├── controllers/
│   │   ├── AtendimentoController.php   # check-in e check-out
│   │   ├── ChamadoController.php       # CRUD de chamados
│   │   ├── PrioridadeController.php    # listagem de prioridades
│   │   └── SetorController.php         # CRUD de setores
│   ├── helpers/
│   │   └── prioridade.php              # regras de urgência centralizadas
│   ├── models/
│   │   ├── Chamado.php                 # queries da tabela chamados
│   │   ├── Prioridade.php             # queries da tabela prioridades
│   │   └── Setor.php                  # queries da tabela setores
│   └── views/
│       ├── atendimento/
│       │   └── index.php
│       ├── chamados/
│       │   ├── create.php
│       │   └── index.php
│       ├── layouts/
│       │   ├── footer.php
│       │   └── header.php
│       ├── prioridades/
│       │   └── index.php
│       └── setores/
│           └── index.php
├── config/
│   └── database.php                    # conexão PDO singleton
├── public/
│   └── index.php                       # router principal
├── sql/
│   ├── schema.sql                      # estrutura do banco
│   └── seed.sql                        # dados iniciais
└── README.md

---

## Como rodar localmente

### Pré-requisitos

- [XAMPP](https://www.apachefriends.org/) instalado
- Apache e MySQL ativos no XAMPP Control Panel
- Git instalado

---

### Passo 1 — Clone o repositório

```bash
git clone https://github.com/ulissesnunes/w5i-helpdesk.git C:/xampp/htdocs/w5i-helpdesk
```

---

### Passo 2 — Crie o banco de dados

Acesse `http://localhost/phpmyadmin`, clique em **Novo** e crie um banco chamado:
helpdesk

---

### Passo 3 — Execute o schema

Selecione o banco `helpdesk`, clique na aba **SQL** e cole o conteúdo do arquivo:
sql/schema.sql

Clique em **Executar**.

---

### Passo 4 — Execute o seed

Ainda na aba SQL, cole o conteúdo do arquivo:
sql/seed.sql

Clique em **Executar**. Isso insere os 4 níveis de prioridade.

---

### Passo 5 — Acesse o sistema
http://localhost/w5i-helpdesk/public/

---

## Decisões técnicas

### Por que PHP puro sem framework?
O escopo do projeto é direto e a proposta do processo seletivo avalia fundamentos. Usar Laravel ou Symfony adicionaria complexidade sem benefício real para o tamanho da aplicação. A arquitetura MVC foi implementada manualmente para demonstrar domínio dos conceitos.

### Por que PDO?
PDO é universal — funciona com MySQL, PostgreSQL e SQLite sem mudança de código. Prepared statements nativos eliminam SQL injection. É o padrão atual do ecossistema PHP.

### Por que os prazos são fixos no sistema?
Urgência é uma regra de negócio — não uma configuração do usuário. Permitir que o usuário defina "quantas horas é crítico" tornaria o sistema inconsistente. O sistema define os prazos com base no nível escolhido.

### Por que Tailwind CDN?
Decisão consciente para o prazo de desenvolvimento. Em produção o correto seria usar o Tailwind CLI para gerar apenas as classes utilizadas, reduzindo o bundle e eliminando a dependência de CDN externo.

---

## Segurança implementada

- **SQL Injection** — todas as queries usam `PDO::prepare()` + `execute()`
- **XSS** — todo output usa `htmlspecialchars()`
- **Método HTTP** — actions POST verificam `$_SERVER['REQUEST_METHOD']`
- **Validação em camadas** — HTML (`required`), PHP (validação explícita), banco (`NOT NULL`, `FOREIGN KEY`)

---

## O que seria implementado em produção

- Autenticação com perfis — cliente (abre chamados) e técnico (atende)
- Paginação na listagem de chamados
- Filtros por status, setor e urgência
- Notificações em tempo real com WebSockets
- Build do Tailwind via CLI
- Variáveis de ambiente para configuração do banco
- Logs de auditoria por chamado