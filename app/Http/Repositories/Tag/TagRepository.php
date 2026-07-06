<?php

namespace App\Http\Repositories\Tag;

use App\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Support\AuthorMap;

/**
 * Class TagRepository
 *
 * Repositório responsável pelo acesso e persistência
 * de dados relacionados à entidade {@see Tag}.
 *
 * Esta camada abstrai o uso direto do Eloquent,
 * permitindo desacoplamento da regra de negócio,
 * maior testabilidade e facilidade de manutenção.
 *
 * @package App\Http\Repositories\Tag
 */
class TagRepository
{
    /**
     * Retorna uma paginação de tags com base nos filtros.
     *
     * @param array $filters Filtros de paginação
     *
     * @return LengthAwarePaginator
     */
    public function paginate(array $filters): LengthAwarePaginator
    {
        return Tag::query()
            ->when(
                $filters['author'] ?? null,
                function ($query, string $author) {
                    $query->whereHas('posts', function ($postQuery) use ($author) {
                        $postQuery->where('author', $author);

                        if (!$this->canSeeDraftsFromAuthor($author)) {
                            $postQuery->where('is_draft', false);
                        }
                    });
                }
            )
            ->when(
                $filters['search'] ?? null,
                fn($q, $search) =>
                    $q->where('name', 'like', "%{$search}%")
            )
            ->orderBy(
                $filters['sort'] ?? 'id',
                $filters['direction'] ?? 'asc'
            )
            ->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Busca uma tag pelo ID.
     *
     * @param int $id ID da tag
     *
     * @return Tag|null
     */
    public function findById(int $id): ?Tag
    {
        return Tag::query()
            ->find($id);
    }

    /**
     * Cria uma nova tag.
     *
     * @param array $data Dados da tag
     *
     * @return Tag
     */
    public function create(array $data): Tag
    {
        return Tag::create($data);
    }

    /**
     * Atualiza uma tag.
     *
     * @param Tag $tag Tag a ser atualizada
     * @param array $data Dados da tag
     *
     * @return Tag
     */
    public function update(Tag $tag, array $data): Tag
    {
        $tag->update($data);

        return $tag;
    }

    /**
     * Deleta uma tag.
     *
     * @param Tag $tag Tag a ser deletada
     *
     * @return void
     */
    public function delete(Tag $tag): void
    {
        $tag->delete();
    }

    private function canSeeDraftsFromAuthor(string $author): bool
    {
        return auth()->check() &&
            auth()->id() === AuthorMap::userIdFromAuthor($author);
    }
}
