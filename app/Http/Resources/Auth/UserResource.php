<?php

namespace App\Http\Resources\Auth;

use App\Support\AuthorMap;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 *
 * Resource responsável pela transformação da entidade
 * {@see User} em uma estrutura JSON
 * padronizada para respostas da API.
 *
 * Garante que apenas os campos necessários sejam
 * expostos ao cliente, evitando vazamento de
 * dados sensíveis e mantendo consistência
 * entre diferentes endpoints.
 *
 * @package App\Http\Resources\Auth
 */
class UserResource extends JsonResource
{
    /**
     * Transforma o recurso em um array serializável.
     *
     * Campos retornados:
     * - id: identificador único do usuário
     * - author: autor do usuário
     * - name: nome completo
     * - age: idade
     * - birth_date: data de nascimento
     * - phone: telefone
     * - email: e-mail
     * - profile_photo: foto de perfil
     * - profile_photo_url: URL da foto de perfil
     * - description: descrição
     * - linkedin: link do linkedin
     * - github: link do github
     * - created_at: data de criação do registro
     *
     * @param Request $request Requisição HTTP atual
     *
     * @return array<string, mixed> Representação do usuário para a API
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author' => AuthorMap::authorFromUserId($this->id),
            'name' => $this->name,
            'age' => $this->age,
            'birth_date' => $this->birth_date,
            'phone' => $this->phone,
            'email' => $this->email,
            'profile_photo' => $this->profile_photo,
            'profile_photo_url' => $this->profile_photo ? asset('storage/' . $this->profile_photo) : null,
            'description' => $this->description,
            'linkedin' => $this->linkedin,
            'github' => $this->github,
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}