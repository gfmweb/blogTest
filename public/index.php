<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Config\Database;
use App\Controller\ArticleController;
use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Mapper\ArticleMapper;
use App\Mapper\CategoryMapper;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Router;
use Smarty\Smarty;

$baseDir = dirname(__DIR__);

$pdo = Database::getConnection();

$smarty = new Smarty();
$smarty->setTemplateDir($baseDir . '/templates');
$smarty->setCompileDir($baseDir . '/templates/templates_c');
$smarty->setCacheDir($baseDir . '/templates/cache');

$categoryMapper = new CategoryMapper();
$articleMapper = new ArticleMapper($categoryMapper);
$categoryRepository = new CategoryRepository($pdo, $categoryMapper);
$articleRepository = new ArticleRepository($pdo, $articleMapper, $categoryRepository);

$router = new Router(
    new HomeController($categoryRepository, $articleRepository, $smarty),
    new CategoryController($categoryRepository, $articleRepository, $smarty),
    new ArticleController($categoryRepository, $articleRepository, $smarty),
);

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

echo $router->dispatch($uri, $method);
