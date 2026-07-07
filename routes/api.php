<?php

use Illuminate\Support\Facades\Route;
use App\Support\AuthorMap;

// Controllers
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\PublicAuthorController;
use App\Http\Controllers\Experience\ExperienceController;
use App\Http\Controllers\Skill\SkillController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Post\PostContentImageController;
use App\Http\Controllers\Tag\TagIconController;
use App\Http\Controllers\Tag\TagController;
use App\Http\Controllers\Todo\TodoController;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

/*
 * |--------------------------------------------------------------------------
 * | Rotas de Autenticação
 * |--------------------------------------------------------------------------
 * |
 * | Este arquivo define os endpoints responsáveis pelo fluxo
 * | de autenticação da API.
 * |
 * | As rotas públicas permitem o login do usuário.
 * | As rotas protegidas exigem autenticação via Sanctum.
 * |
 */

Route::middleware('web')->group(function () {
    /**
     * Realiza o login do usuário.
     *
     * Endpoint público responsável por autenticar o usuário
     * com e-mail e senha.
     *
     * Retorna:
     * - Dados do usuário autenticado
     */
    Route::post('login', [AuthController::class, 'login']);

    /**
     * Registra um novo usuário no sistema.
     *
     * Endpoint público responsável por criar uma nova conta
     * de usuário com os dados básicos necessários.
     *
     * Retorna:
     * - Mensagem de sucesso
     * - Dados do usuário criado
     */
    Route::post('register', [UserController::class, 'store']);
});

/**
 * Rotas de Post
 *
 * Rotas públicas que permitem o acesso aos posts
 * sem necessidade de autenticação.
 */
Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index']);
    Route::get('/{id}', [PostController::class, 'show'])
        ->whereNumber('id');
});

/**
 * Rota pública para buscar autor pelo nome.
 */
Route::get('/authors/{author}', [PublicAuthorController::class, 'show'])
    ->whereIn('author', array_keys(AuthorMap::all()));

/**
 * Rotas de Tag
 *
 * Rotas públicas que permitem o acesso às tags
 * sem necessidade de autenticação.
 */
Route::prefix('tags')->group(function () {
    Route::get('/', [TagController::class, 'index']);
    Route::get('/icons', [TagIconController::class, 'index']);
    Route::get('/{id}', [TagController::class, 'show'])
        ->whereNumber('id');
});

/*
 * |--------------------------------------------------------------------------
 * | Rotas Protegidas (auth:sanctum)
 * |--------------------------------------------------------------------------
 * |
 * | As rotas abaixo exigem que o usuário esteja autenticado
 * | via Laravel Sanctum.
 * |
 */
Route::middleware('auth:sanctum')->group(function () {
    /**
     * Perfil do usuário
     * 
     * Rotas para gerenciar o perfil do usuário autenticado.
     */
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/me/profile', [ProfileController::class, 'update']);
    Route::put('/me/password', [ProfileController::class, 'updatePassword']);

    Route::prefix('me/experiences')->group(function () {
        Route::get('/', [ExperienceController::class, 'index']);
        Route::post('/', [ExperienceController::class, 'store']);
        Route::put('/{id}', [ExperienceController::class, 'update'])->whereNumber('id');
        Route::delete('/{id}', [ExperienceController::class, 'destroy'])->whereNumber('id');
    });

    Route::prefix('me/skills')->group(function () {
        Route::get('/', [SkillController::class, 'index']);
        Route::post('/', [SkillController::class, 'store']);
        Route::put('/{id}', [SkillController::class, 'update'])->whereNumber('id');
        Route::delete('/{id}', [SkillController::class, 'destroy'])->whereNumber('id');
    });

    /**
     * Realiza o logout do usuário autenticado.
     *
     * Invalida o token de acesso atual,
     * encerrando a sessão da API.
     */
    Route::post('/logout', [AuthController::class, 'logout']);

    /**
     * Rotas de Post
     *
     * Rotas protegidas que permitem o gerenciamento de posts
     * pelo usuário autenticado.
     */
    Route::prefix('posts')->group(function () {
        Route::post('/', [PostController::class, 'store']);
        Route::put('/{id}', [PostController::class, 'update'])->whereNumber('id');
        Route::delete('/{id}', [PostController::class, 'destroy'])->whereNumber('id');
        Route::post('/content-images', [PostContentImageController::class, 'store']);
    });

    /**
     * Rotas de Tag
     *
     * Rotas protegidas que permitem o gerenciamento de tags
     * pelo usuário autenticado.
     */
    Route::prefix('tags')->group(function () {
        Route::post('/', [TagController::class, 'store']);
        Route::put('/{id}', [TagController::class, 'update'])->whereNumber('id');
        Route::delete('/{id}', [TagController::class, 'destroy'])->whereNumber('id');
    });

    /**
     * Rotas de Todo
     *
     * Rotas protegidas que permitem o gerenciamento de todos
     * pelo usuário autenticado.
     */
    Route::prefix('todos')->group(function () {
        Route::get('/', [TodoController::class, 'index']);
        Route::post('/', [TodoController::class, 'store']);
        Route::get('/{id}', [TodoController::class, 'show'])->whereNumber('id');
        Route::put('/{id}', [TodoController::class, 'update'])->whereNumber('id');
        Route::patch('/{id}/status', [TodoController::class, 'updateStatus'])->whereNumber('id');
        Route::delete('/{id}', [TodoController::class, 'destroy'])->whereNumber('id');
    });
});
