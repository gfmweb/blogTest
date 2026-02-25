<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Smarty\Smarty;

final class ArticleController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly ArticleRepository $articleRepository,
        private readonly Smarty $smarty,
    ) {
    }

    public function show(string $slug): string
    {
        $article = $this->articleRepository->getBySlug($slug);
        if ($article === null) {
            http_response_code(404);
            $this->smarty->assign('pageTitle', '404 — Не найдено');
            return $this->smarty->fetch('404.tpl');
        }

        $this->articleRepository->incrementViewCount($article->id);

        $categoryIds = array_map(fn ($c) => $c->id, $article->categories);
        $similar = $this->articleRepository->getSimilar($article->id, $categoryIds, 3);

        $this->smarty->assign('pageTitle', $article->name . ' — Блог');
        $this->smarty->assign('article', $article);
        $this->smarty->assign('similar', $similar);

        return $this->smarty->fetch('article.tpl');
    }
}
