<?php

namespace App\Http\Services\Tag;

/**
 * Class TagIconService
 * 
 * Serviço responsável por fornecer os ícones disponíveis para uso em tags.
 * 
 * @package App\Http\Services\Tag
 */
class TagIconService
{
    /**
     * Lista todos os ícones disponíveis.
     * 
     * @return array<int, array<string, string>>
     */
    public function list(): array
    {
        return TagIconCatalog::all();
    }
}