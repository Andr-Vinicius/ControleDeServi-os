# Sistema de Controle de Serviços — JM Informática

![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-PDO-4479A1?logo=mysql&logoColor=white)
![Arquitetura](https://img.shields.io/badge/Arquitetura-MVC-blue)
![Sem framework](https://img.shields.io/badge/Framework-nenhum-lightgrey)


## Índice

- [Tecnologias](#tecnologias)
- [Estrutura do projeto](#estrutura-do-projeto)
- [Pré-requisitos](#pré-requisitos)
- [Como rodar (XAMPP / WAMP / MAMP)](#como-rodar-xampp--wamp--mamp)
- [Funcionalidades implementadas](#funcionalidades-implementadas)
- [Regra de comissão](#regra-de-comissão)
- [Sobre o envio de email](#sobre-o-envio-de-email)
- [Observações de implementação](#observações-de-implementação)

## Tecnologias

- PHP orientado a objetos, arquitetura MVC própria (sem framework)
- PDO com MySQL (prepared statements em todas as queries)
- JavaScript puro (sem jQuery)
- HTML/CSS próprios, sem framework de front-end

## Estrutura do projeto

```
Projeto/
├── app/
│   ├── Controllers/    AuthController, DashboardController, ServiceController
│   ├── Core/            Router, Controller (base), Database (PDO singleton)
│   ├── Models/          User, Service
│   └── Views/           auth, dashboard, service, layouts (header/footer)
├── config/config.php    Credenciais de conexão com o MySQL
├── database/schema.sql  Script de criação das tabelas + dados de exemplo
├── public/              Document root (index.php, .htaccess, css, js)
└── routes.php           Definição de todas as rotas
```

## Pré-requisitos

- PHP 8+ com extensão PDO/MySQL habilitada
- MySQL/MariaDB
- Apache com `mod_rewrite` habilitado (usado pelo `.htaccess` em `public/`)

## Como rodar (XAMPP / WAMP / MAMP)

1. Copie a pasta `Projeto` para dentro do `htdocs` do seu Apache.
2. Crie o banco executando o script `database/schema.sql` no MySQL/phpMyAdmin.
   Ele já cria o banco `titan_teste`, as tabelas `user`/`service` e alguns
   registros de exemplo para facilitar o teste.
3. Ajuste `config/config.php` se o usuário/senha do seu MySQL forem diferentes
   de `root` / (senha em branco).
4. Acesse `http://localhost/Projeto/public/` no navegador.

> A aplicação calcula a `BASE_URL` automaticamente a partir do próprio script
> (`public/index.php`), então funciona independente do nome/local da pasta
> dentro do `htdocs` — não é necessário editar links manualmente.


## Funcionalidades implementadas

- **Login** com validação de email/senha e mensagem `Ops, Email ou Senha inválido`.
- **Cadastro de usuário** (tela extra sugerida pelo wireframe).
- **Dashboard**:
  - Dados do usuário logado, data atual e tabela de serviços prestados
    (id, descrição, usuário, valor, status) com botões **Alterar**, **Excluir**
    e **Finalizar**.
  - Valor total dos serviços prestados **pelo usuário logado**, em destaque.
  - Lista de serviços pendentes do usuário logado.
  - Filtros por período (data inicial/final), nome do serviço, status e
    nome do usuário — combináveis entre si.
- **Cadastro de novo serviço**: cria com status "Pendente"; em caso de falha
  (campos obrigatórios ausentes ou inválidos) volta ao dashboard com mensagem
  de erro, sem cadastrar nada — igual ao especificado no enunciado.
- **Alterar serviço**: formulário de edição de descrição/valor.
- **Excluir serviço**: com confirmação via JS antes de enviar.
- **Finalizar serviço**: grava `finished_at`, calcula a comissão do usuário
  e dispara um email de notificação (função nativa `mail()` do PHP).

## Regra de comissão

O enunciado define:

- até R$ 250,00 → 5%
- acima de R$ 1.000,00 → 10%
- acima de R$ 10.000,00 → 20%

**Observação**: a faixa entre R$ 250,01 e R$ 1.000,00 não foi especificada.
Foi adotado **5%** para esse intervalo também.

## Sobre o envio de email

O envio usa a função nativa `mail()` do PHP. Para testar em ambiente local (XAMPP/WAMP) eu utilizei o Mailhog. 

1. Acesse o repositório https://github.com/mailhog/MailHog/releases e baixe o executável relacionado ao seu Sistema Operacional.
2. Configure os dados abaixo no php.init (Lembre-se de comentar as linhas duplicadas)
[mail function]
SMTP = localhost
smtp_port = 1025
sendmail_from = no-reply@sistema-servicos.local
4. Inicie o executável e acesse http://localhost:8025 no navegador para que possa testar o envio de emails.


## Observações de implementação

- Todas as queries usam PDO com prepared statements.
- Senhas armazenadas com `password_hash()`/`password_verify()` (bcrypt).
- `session_regenerate_id()` no login, para mitigar session fixation.
- Rotas centralizadas em `routes.php`, roteador simples em `App\Core\Router`.
