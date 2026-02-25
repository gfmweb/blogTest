<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Smarty\Smarty;

final class HomeController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly ArticleRepository $articleRepository,
        private readonly Smarty $smarty,
    ) {
    }

    public function index(): string
    {
        $categories = $this->categoryRepository->getOnlyWithArticles();
        $articlesByCategoryId = $this->articleRepository->getLatestGroupedByCategory(3);
        $categoriesWithArticles = [];
        foreach ($categories as $category) {
            $categoriesWithArticles[] = [
                'category' => $category,
                'articles' => $articlesByCategoryId[$category->id] ?? [],
            ];
        }
        $this->smarty->assign('pageTitle', 'Блог');
        $this->smarty->assign('categoriesWithArticles', $categoriesWithArticles);
        return $this->smarty->fetch('home.tpl');
    }
}
