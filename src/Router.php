<?php

declare(strict_types=1);

namespace App;

use App\Controller\ArticleController;
use App\Controller\CategoryController;
use App\Controller\HomeController;

final class Router
{
    public function __construct(
        private readonly HomeController $homeController,
        private readonly CategoryController $categoryController,
        private readonly ArticleController $articleController,
    ) {
    }

    public function dispatch(string $uri, string $method = 'GET'): string
    {
        $path = $this->normalizePath($uri);
        if ($method !== 'GET') {
            return $this->notFound();
        }
        if ($path === '/') {
            return $this->homeController->index();
        }
        if (preg_match('#^/category/([a-z0-9\-]+)$#', $path, $m)) {
            $query = parse_url($uri, PHP_URL_QUERY) ?: '';
            parse_str($query, $params);
            $page = isset($params['page']) ? max(1, (int) $params['page']) : 1;
            $sortBy = isset($params['sort']) && $params['sort'] === 'views' ? 'view_count' : 'published_at';
            return $this->categoryController->show($m[1], $page, $sortBy);
        }
        if (preg_match('#^/article/([a-z0-9\-]+)$#', $path, $m)) {
            return $this->articleController->show($m[1]);
        }
        return $this->notFound();
    }

    private function normalizePath(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH);
        if ($path === false || $path === '') {
            return '/';
        }
        $path = '/' . trim($path, '/');
        return $path === '' ? '/' : $path;
    }

    private function notFound(): string
    {
        http_response_code(404);
        return '404 Not Found';
    }
}
