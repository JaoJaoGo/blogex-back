<?php

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Services\Tag\TagIconCatalog;

/**
 * Class UpdateTagRequest
 *
 * Request responsável pela validação dos dados
 * necessários para atualização de uma tag.
 *
 * Centraliza as regras de validação do cadastro,
 * garantindo consistência entre diferentes pontos
 * de entrada (API, CLI, etc.).
 *
 * @package App\Http\Requests\Tag
 */
class UpdateTagRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado
     * a realizar esta requisição.
     *
     * O sistema de autorização será feito via middleware.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Retorna as regras de validação aplicáveis à requisição.
     *
     * Campos validados:
     * - name: nome da tag
     * - icon: identificador do ícone da tag
     * - color: cor hexadecimal da tag
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'icon' => ['sometimes', 'string', Rule::in(TagIconCatalog::keys()),],
            'color' => ['sometimes', 'string', 'regex:/^#[A-Fa-f0-9]{6}$/'],
        ];
    }

    /**
     * Retorna as mensagens de validação personalizadas.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.string' => 'O nome da tag deve ser uma string.',
            'name.max' => 'O nome da tag deve ter no máximo 255 caracteres.',

            'icon.string' => 'O ícone da tag deve ser uma string.',
            'icon.max' => 'O ícone da tag deve ter no máximo 100 caracteres.',

            'color.string' => 'A cor da tag deve ser uma string.',
            'color.regex' => 'A cor da tag deve estar no formato hexadecimal. Ex: #8b5cf6.',
        ];
    }

    /**
     * Dados já sanitizados para atualização da tag.
     *
     * Remove espaços extras e normaliza valores.
     *
     * @return array<string, mixed>
     */
    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        if ($key !== null || ! is_array($data)) {
            return $data;
        }

        if (isset($data['name'])) {
            $data['name'] = trim($data['name']);
        }

        if (isset($data['icon'])) {
            $data['icon'] = trim($data['icon']);
        }

        if (isset($data['color'])) {
            $data['color'] = strtolower(trim($data['color']));
        }

        return $data;
    }
}