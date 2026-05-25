<?php

namespace App\Http\Controllers\Tag;

use App\Http\Controllers\Controller;
use App\Http\Responses\Tag\ListTagIconsResponse;
use App\Http\Services\Tag\TagIconService;
use Illuminate\Http\JsonResponse;

/**
 * Class TagIconController
 * 
 * Controller responsável por listar os ícones disponíveis para uso em tags.
 * 
 * @package App\Http\Controllers\Tag
 */
class TagIconController extends Controller
{
    public function __construct(
        protected TagIconService $tagIconService
    ) {}

    /**
     * Lista os ícones disponíveis para tags.
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $icons = $this->tagIconService->list();

        return ListTagIconsResponse::fromArray($icons);
    }
}