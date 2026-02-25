<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Smarty\Smarty;

final class CategoryController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly ArticleRepository $articleRepository,
        private readonly Smarty $smarty,
    ) {
    }

    public function show(string $slug, int $page = 1, string $sortBy = 'published_at'): string
    {
        $category = $this->categoryRepository->getBySlug($slug);
        if ($category === null) {
            http_response_code(404);
            $this->smarty->assign('pageTitle', '404 — Не найдено');
            return $this->smarty->fetch('404.tpl');
        }

        $perPage = 5;
        $result = $this->articleRepository->getByCategoryIdPaginated($category->id, $page, $perPage, $sortBy);
        $totalPages = (int) max(1, ceil($result['total'] / $perPage));
        $page = min(max(1, $page), $totalPages);

        $this->smarty->assign('category', $category);
        $this->smarty->assign('articles', $result['items']);
        $this->smarty->assign('total', $result['total']);
        $this->smarty->assign('page', $page);
        $this->smarty->assign('totalPages', $totalPages);
        $this->smarty->assign('sortBy', $sortBy);
        $this->smarty->assign('pageTitle', $category->name . ' — Блог');

        return $this->smarty->fetch('category.tpl');
    }
}
