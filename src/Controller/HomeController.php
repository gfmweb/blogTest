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
        $categoriesWithArticles = [];
        foreach ($categories as $category) {
            $articles = $this->articleRepository->getLatestByCategoryId($category->id, 3);
            $categoriesWithArticles[] = [
                'category' => $category,
                'articles' => $articles,
            ];
        }
        $this->smarty->assign('categoriesWithArticles', $categoriesWithArticles);
        return $this->smarty->fetch('home.tpl');
    }
}
