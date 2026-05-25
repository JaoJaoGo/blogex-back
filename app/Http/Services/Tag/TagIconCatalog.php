<?php

namespace App\Http\Services\Tag;

/**
 * Class TagIconCatalog
 * 
 * Catálogo responsável por centralizar os ícones disponíveis para tags
 * 
 * Como os ícones são predefinidos pelo sistema, este catálogo atua como fonte única de verdade para criação, edição e validação de tags.
 * 
 * @package App\Http\Services\Tag
 */
class TagIconCatalog
{
    /**
     * Lista de ícones disponíveis.
     * 
     * O campo "key" deve ser salvo no banco no campo tags.icon.
     * 
     * @return array<int, array<string, string>>
     */
    public static function all(): array
    {
        return [
            [
                'key' => 'code',
                'label' => 'Código',
            ],
            [
                'key' => 'terminal',
                'label' => 'Terminal',
            ],
            [
                'key' => 'database',
                'label' => 'Banco de Dados',
            ],
            [
                'key' => 'palette',
                'label' => 'Design',
            ],
            [
                'key' => 'figma',
                'label' => 'Figma',
            ],
            [
                'key' => 'pen',
                'label' => 'Escrita',
            ],
            [
                'key' => 'book-open',
                'label' => 'Artigo',
            ],
            [
                'key' => 'rocket',
                'label' => 'Projeto',
            ],
            [
                'key' => 'brain',
                'label' => 'Ideia',
            ],
            [
                'key' => 'sparkles',
                'label' => 'Criativo',
            ],
        ];
    }

    /**
     * Retorna apenas as chaves dos ícones.
     * 
     * Útil para validação via Rule::in().
     * 
     * @return array<int, string>
     */
    public static function keys(): array
    {
        return array_column(
            self::all(),
            'key'
        );
    }
}