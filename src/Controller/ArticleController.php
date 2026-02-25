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
        return 'OK';
    }
}
