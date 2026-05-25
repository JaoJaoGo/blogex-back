# Blogex - Backend

Backend da aplicação Blogex, um sistema de blog com suporte a múltiplos autores, tags e editor de texto rico. Desenvolvido com Laravel 12, Sanctum para autenticação SPA e arquitetura orientada a serviços.

## Stack Tecnológica

- **PHP 8.2+** - Linguagem principal
- **Laravel 12** - Framework PHP
- **Laravel Sanctum** - Autenticação SPA (API tokens e sessões)
- **SQLite** - Banco de dados (configurado para desenvolvimento)
- **PHPUnit 11** - Testes automatizados
- **Laravel Pint** - Linting e formatação de código
- **Laravel Sail** - Docker para desenvolvimento local
- **Laravel Pail** - Logs em tempo real

## Estrutura do Projeto

```
app/
├── Console/
│   └── Commands/         # Comandos Artisan customizados
├── Http/
│   ├── Controllers/      # Controladores da API
│   │   ├── Auth/         # Autenticação e usuários
│   │   ├── Post/         # Gerenciamento de posts
│   │   └── Tag/          # Gerenciamento de tags
│   ├── Requests/         # FormRequest para validação
│   ├── Resources/        # API Resources para formatação JSON
│   ├── Responses/        # Respostas padronizadas
│   ├── Services/         # Lógica de negócio
│   │   ├── Auth/         # Serviços de autenticação
│   │   ├── Post/         # Serviços de posts
│   │   └── Tag/          # Serviços de tags
│   └── Repositories/     # Padrão Repository (opcional)
├── Models/               # Models Eloquent
│   ├── User.php
│   ├── Post.php
│   └── Tag.php
└── Providers/            # Service Providers

database/
├── factories/            # Model Factories para testes
├── migrations/           # Migrations do banco de dados
└── seeders/              # Seeders para dados iniciais

routes/
├── api.php               # Rotas da API REST
├── web.php               # Rotas web (CSRF, sessions)
└── console.php           # Comandos e scheduling (Schedule::command)
```

## Funcionalidades

- **Autenticação SPA** com Laravel Sanctum (cookies e stateful domains)
- **CRUD de Posts** com validação e autorização
- **Sistema de Tags** com suporte a ícones e cores customizáveis
- **Gerenciamento de Usuários** com registro e login
- **API RESTful** com Resources padronizados
- **Validação centralizada** com FormRequest
- **Arquitetura de Serviços** para separação de responsabilidades
- **Respostas padronizadas** para consistência da API

## Scripts Composer

```bash
composer setup           # Setup completo do projeto
composer dev             # Inicia servidor, queue, logs e Vite
composer test            # Executa testes automatizados
```

## Variáveis de Ambiente

Configure as variáveis no arquivo `.env`:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
APP_FRONT_URL=https://blogex.test

SANCTUM_STATEFUL_DOMAINS=blogex.test
AUTH_GUARD=web

APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR
APP_FAKER_LOCALE=pt_BR

DB_CONNECTION=sqlite
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

## Configuração HTTPS Local

Para desenvolvimento com o frontend React, configure o domínio local:

### 1. Configure o hosts file

Adicione ao seu arquivo `hosts` (Windows: `C:\Windows\System32\drivers\etc\hosts`):
```
127.0.0.1 blogex.test
```

### 2. Configure o .env

```env
APP_URL=https://blogex.test
APP_FRONT_URL=https://blogex.test
SANCTUM_STATEFUL_DOMAINS=blogex.test
```

## Desenvolvimento

### Setup Inicial

1. Instale as dependências:
```bash
composer install
```

2. Configure o ambiente:
```bash
cp .env.example .env
php artisan key:generate
```

3. Execute as migrations:
```bash
php artisan migrate
```

4. Inicie o servidor de desenvolvimento:
```bash
php artisan serve
```

### Modo Desenvolvimento Completo

Para iniciar todos os serviços (servidor, queue, logs, Vite):
```bash
composer dev
```

### Executar Testes

```bash
composer test
# ou
php artisan test
```

## Estrutura de API

### Autenticação
- `POST /api/login` - Login de usuário
- `POST /api/register` - Registro de novo usuário
- `POST /api/logout` - Logout (autenticado)
- `GET /api/user` - Dados do usuário autenticado

### Posts
- `GET /api/posts` - Listar posts
- `GET /api/posts/{id}` - Detalhes do post
- `POST /api/posts` - Criar post (autenticado)
- `PUT /api/posts/{id}` - Atualizar post (autenticado)
- `DELETE /api/posts/{id}` - Deletar post (autenticado)

### Tags
- `GET /api/tags` - Listar tags
- `GET /api/tags/{id}` - Detalhes da tag
- `POST /api/tags` - Criar tag (autenticado)
- `PUT /api/tags/{id}` - Atualizar tag (autenticado)
- `DELETE /api/tags/{id}` - Deletar tag (autenticado)
- `PUT /api/tags/{id}/icon` - Atualizar ícone da tag (autenticado)

## Boas Práticas Implementadas

- **FormRequest** para validação centralizada e segura
- **API Resources** para padronização de respostas JSON
- **Services** para separação de lógica de negócio
- **Controllers enxutos** (máximo 50 linhas)
- **Eager loading** para evitar N+1 queries
- **Middleware de autenticação** e throttling
- **Policies** para autorização de recursos
- **Migrations** versionadas para controle de schema

## Scheduling

O scheduling de tarefas é configurado em `routes/console.php` usando `Schedule::command()`, conforme as diretrizes do projeto.

## Integração com Frontend

O backend se integra com o frontend React através da API REST em `/api`. A autenticação usa cookies e Laravel Sanctum com stateful domains configurados para o domínio do frontend.
