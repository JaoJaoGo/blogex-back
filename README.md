# Blogex - Backend

Backend da aplicação Blogex, um sistema de blog com suporte a múltiplos autores, tags, editor de texto rico, gerenciamento de perfil, experiências, skills e sistema de todos. Desenvolvido com Laravel 12, Sanctum para autenticação SPA e arquitetura orientada a serviços.

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
├── Console/              # Comandos Artisan customizados
├── Http/
│   ├── Controllers/     # Controladores da API
│   │   ├── Auth/         # Autenticação, usuários e perfil
│   │   ├── Experience/   # Gerenciamento de experiências
│   │   ├── Post/         # Gerenciamento de posts e mídia
│   │   ├── Skill/        # Gerenciamento de skills
│   │   ├── Tag/          # Gerenciamento de tags
│   │   └── Todo/         # Gerenciamento de todos
│   ├── Requests/         # FormRequest para validação
│   ├── Resources/        # API Resources para formatação JSON
│   ├── Responses/        # Respostas padronizadas
│   ├── Services/         # Lógica de negócio
│   │   ├── Auth/         # Serviços de autenticação
│   │   ├── Experience/   # Serviços de experiências
│   │   ├── Post/         # Serviços de posts
│   │   ├── Skill/        # Serviços de skills
│   │   ├── Tag/          # Serviços de tags
│   │   └── Todo/         # Serviços de todos
│   └── Repositories/     # Padrão Repository
├── Jobs/                 # Jobs para filas (limpeza de todos e mídias)
├── Models/               # Models Eloquent
│   ├── User.php
│   ├── Post.php
│   ├── Tag.php
│   ├── Experience.php
│   ├── Skill.php
│   ├── Todo.php
│   └── TodoChecklist.php
├── Support/              # Classes de suporte (AuthorMap)
└── Providers/            # Service Providers

database/
├── factories/            # Model Factories para testes
├── migrations/           # Migrations do banco de dados
└── seeders/              # Seeders para dados iniciais

routes/
├── api.php               # Rotas da API REST
├── web.php               # Rotas web (CSRF, sessions)
└── console.php           # Comandos e scheduling (Schedule::command)

tests/
├── Feature/              # Testes de integração (Auth, Post)
├── Unit/                 # Testes unitários (Services, Repositories, Requests, Resources, Responses)
└── README.md             # Documentação detalhada dos testes
```

## Funcionalidades

- **Autenticação SPA** com Laravel Sanctum (cookies e stateful domains)
- **Gerenciamento de Usuários** com registro, login e perfil
- **Perfil do Usuário** com atualização de dados e senha
- **CRUD de Posts** com validação, autorização e filtros
- **Sistema de Tags** com suporte a ícones e cores customizáveis
- **Gerenciamento de Experiências** (CRUD completo)
- **Gerenciamento de Skills** (CRUD completo)
- **Sistema de Todos** com checklist e status
- **Upload de Mídia** para conteúdo de posts
- **Link Preview** para posts
- **Autores Públicos** com mapeamento de autores
- **API RESTful** com Resources padronizados
- **Validação centralizada** com FormRequest
- **Arquitetura de Serviços** para separação de responsabilidades
- **Respostas padronizadas** para consistência da API
- **Jobs em Filas** para limpeza de dados (todos e mídias órfãs)

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

### Autenticação (Públicas)
- `POST /api/login` - Login de usuário
- `POST /api/register` - Registro de novo usuário

### Autenticação (Protegidas)
- `GET /api/me` - Dados do usuário autenticado
- `POST /api/me/profile` - Atualizar perfil do usuário
- `PUT /api/me/password` - Atualizar senha do usuário
- `POST /api/logout` - Logout

### Posts (Públicas)
- `GET /api/posts` - Listar posts (com filtros: author, search, tags)
- `GET /api/posts/{id}` - Detalhes do post

### Posts (Protegidas)
- `POST /api/posts` - Criar post
- `PUT /api/posts/{id}` - Atualizar post
- `DELETE /api/posts/{id}` - Deletar post
- `POST /api/posts/content-media` - Upload de mídia para conteúdo
- `POST /api/posts/link-preview` - Obter preview de link

### Tags (Públicas)
- `GET /api/tags` - Listar tags
- `GET /api/tags/icons` - Listar ícones disponíveis
- `GET /api/tags/{id}` - Detalhes da tag

### Tags (Protegidas)
- `POST /api/tags` - Criar tag
- `PUT /api/tags/{id}` - Atualizar tag
- `DELETE /api/tags/{id}` - Deletar tag

### Experiências (Protegidas)
- `GET /api/me/experiences` - Listar experiências do usuário
- `POST /api/me/experiences` - Criar experiência
- `PUT /api/me/experiences/{id}` - Atualizar experiência
- `DELETE /api/me/experiences/{id}` - Deletar experiência

### Skills (Protegidas)
- `GET /api/me/skills` - Listar skills do usuário
- `POST /api/me/skills` - Criar skill
- `PUT /api/me/skills/{id}` - Atualizar skill
- `DELETE /api/me/skills/{id}` - Deletar skill

### Todos (Protegidas)
- `GET /api/todos` - Listar todos do usuário
- `POST /api/todos` - Criar todo
- `GET /api/todos/{id}` - Detalhes do todo
- `PUT /api/todos/{id}` - Atualizar todo
- `PATCH /api/todos/{id}/status` - Atualizar status do todo
- `DELETE /api/todos/{id}` - Deletar todo

### Autores (Públicas)
- `GET /api/authors/{author}` - Buscar autor pelo nome (mapeado em AuthorMap)

## Boas Práticas Implementadas

- **FormRequest** para validação centralizada e segura
- **API Resources** para padronização de respostas JSON
- **Services** para separação de lógica de negócio
- **Controllers enxutos** (máximo 50 linhas)
- **Eager loading** para evitar N+1 queries
- **Middleware de autenticação** e throttling
- **Policies** para autorização de recursos
- **Migrations** versionadas para controle de schema
- **Jobs** para processamento assíncrono (limpeza de dados)
- **Repositories** para abstração de acesso a dados
- **Responses** padronizadas para consistência da API

## Scheduling

O scheduling de tarefas é configurado em `routes/console.php` usando `Schedule::command()`, conforme as diretrizes do projeto.

## Jobs Implementados

- **ClearCompletedTodosJob** - Limpa todos completados periodicamente
- **ClearOrphanPostContentMediasJob** - Remove mídias de conteúdo de posts órfãs

## Testes

O projeto possui uma suite de testes abrangente com **203 testes** (69 Feature + 134 Unit):

- **Testes de Feature**: Autenticação (login, registro, logout, perfil) e Posts (CRUD, filtros, mídia)
- **Testes Unitários**: Services, Repositories, Requests, Resources e Responses

Para mais detalhes sobre a estrutura e cobertura dos testes, consulte `tests/README.md`.

### Executar Testes

```bash
composer test
# ou
php artisan test
```

## Integração com Frontend

O backend se integra com o frontend React através da API REST em `/api`. A autenticação usa cookies e Laravel Sanctum com stateful domains configurados para o domínio do frontend.

## Modelos de Dados

### User
- Representa os usuários do sistema
- Campos: nome, email, senha, data de nascimento, telefone, bio, avatar
- Relacionamentos: posts, experiences, skills, todos

### Post
- Representa os posts do blog
- Campos: título, conteúdo, slug, imagem destacada, status
- Relacionamentos: author (User), tags

### Tag
- Representa as tags para categorização
- Campos: nome, slug, cor, ícone
- Relacionamentos: posts

### Experience
- Representa as experiências profissionais do usuário
- Campos: empresa, cargo, período, descrição
- Relacionamentos: user

### Skill
- Representa as habilidades do usuário
- Campos: nome, nível, categoria
- Relacionamentos: user

### Todo
- Representa as tarefas do usuário
- Campos: título, descrição, status, data limite
- Relacionamentos: user, checklist (TodoChecklist)

### TodoChecklist
- Representa os itens de checklist de um todo
- Campos: descrição, completado
- Relacionamentos: todo

## Configurações Adicionais

### AutorMap
- Classe de suporte em `app/Support/AuthorMap.php`
- Mapeia nomes de autores para IDs de usuários
- Utilizado para rotas públicas de autores

### Filas
- Configurado para usar database como driver
- Jobs implementados para limpeza automática de dados
- Execute `php artisan queue:work` para processar jobs

### Logs
- Laravel Pail configurado para logs em tempo real
- Execute `php artisan pail` para visualizar logs
- Incluído no script `composer dev`

## Comandos Úteis

```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Executar migrations
php artisan migrate
php artisan migrate:fresh
php artisan migrate:rollback

# Executar jobs manualmente
php artisan queue:work
php artisan queue:listen

# Verificar rotas
php artisan route:list
php artisan route:list --path=api

# Linting de código
./vendor/bin/pint

# Logs em tempo real
php artisan pail
```

## Ambiente de Desenvolvimento

### Requisitos
- PHP 8.2 ou superior
- Composer
- Node.js e NPM (para assets do frontend)
- SQLite (para desenvolvimento) ou MySQL/PostgreSQL (para produção)

### Configuração Inicial

1. Clone o repositório e navegue até o diretório `back`
2. Instale as dependências PHP:
```bash
composer install
```

3. Configure o ambiente:
```bash
cp .env.example .env
php artisan key:generate
```

4. Execute as migrations:
```bash
php artisan migrate
```

5. (Opcional) Execute os seeders para dados de teste:
```bash
php artisan db:seed
```

### Modo Desenvolvimento

Para iniciar todos os serviços (servidor, queue, logs, Vite):
```bash
composer dev
```

Este comando inicia:
- Servidor PHP (`php artisan serve`)
- Queue worker (`php artisan queue:listen`)
- Logs em tempo real (`php artisan pail`)
- Vite para assets (`npm run dev`)

### Testes

O projeto possui uma suite de testes abrangente. Consulte `tests/README.md` para detalhes completos.

```bash
# Executar todos os testes
composer test

# Executar com coverage
php artisan test --coverage

# Executar teste específico
php artisan test tests/Feature/Auth/AuthControllerTest.php
```

## Segurança

- Todas as rotas sensíveis são protegidas por `auth:sanctum`
- Validação de dados via FormRequest
- Sanitização de inputs
- Proteção CSRF habilitada
- Throttling configurado para prevenir ataques de força bruta
- Senhas hash usando bcrypt
- Campos sensíveis ocultos em Resources (senha, token, etc.)

## Deploy

### Para Produção

1. Configure o `.env` para produção:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio.com
APP_FRONT_URL=https://seu-dominio.com
SANCTUM_STATEFUL_DOMAINS=seu-dominio.com
```

2. Use um banco de dados robusto (MySQL/PostgreSQL):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blogex
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

3. Otimize a aplicação:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

4. Configure o servidor web (Nginx/Apache) para apontar para o diretório `public`

5. Configure o processo de queue worker:
```bash
php artisan queue:work --daemon
```

6. Configure o cron job para o scheduler:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Troubleshooting

### Problemas Comuns

**Erro de conexão com banco de dados:**
- Verifique se o arquivo `database/database.sqlite` existe
- Ou configure corretamente as variáveis `DB_*` no `.env`

**Erro de permissão:**
- Garanta que o diretório `storage` e `bootstrap/cache` tenham permissões de escrita

**Jobs não sendo processados:**
- Execute `php artisan queue:work` ou configure o cron job
- Verifique se `QUEUE_CONNECTION` está configurado corretamente

**Erro de CORS/CSRF:**
- Configure `SANCTUM_STATEFUL_DOMAINS` no `.env`
- Verifique se `APP_FRONT_URL` está correto

## Contribuição

Este projeto segue as diretrizes de desenvolvimento WiseData:
- Código funcional e declarativo
- Arquitetura orientada a serviços
- Controllers enxutos (máximo 50 linhas)
- Separação de responsabilidades
- Testes abrangentes
- Documentação clara

## Licença

Este projeto é propriedade de João Víctor Guedes Carrijo.
