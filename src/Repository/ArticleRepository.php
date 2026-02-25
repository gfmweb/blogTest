<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\ArticleDto;
use App\Mapper\ArticleMapper;
use PDO;

final class ArticleRepository
{
    private const DEFAULT_PER_PAGE = 5;

    public function __construct(
        private readonly PDO $pdo,
        private readonly ArticleMapper $mapper,
        private readonly CategoryRepository $categoryRepository,
    ) {
    }

    public function getBySlug(string $slug): ?ArticleDto
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, slug, image, name, description, text, view_count, published_at, created_at
             FROM articles WHERE slug = ? LIMIT 1'
        );
        $stmt->execute([$slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $categoryRows = $this->fetchCategoryRowsForArticle((int) $row['id']);
        return $this->mapper->fromRow($row, $categoryRows);
    }

    /** @return ArticleDto[] */
    public function getLatestByCategoryId(int $categoryId, int $limit = 3): array
    {
        $limit = max(1, min(100, $limit));
        $stmt = $this->pdo->prepare(
            'SELECT a.id, a.slug, a.image, a.name, a.description, a.text, a.view_count, a.published_at, a.created_at
             FROM articles a
             INNER JOIN article_category ac ON ac.article_id = a.id AND ac.category_id = ?
             ORDER BY a.published_at DESC
             LIMIT ' . $limit
        );
        $stmt->execute([$categoryId]);
        return $this->fetchAllFromStatement($stmt);
    }

    /**
     * @return array{items: ArticleDto[], total: int}
     */
    public function getByCategoryIdPaginated(
        int $categoryId,
        int $page,
        int $perPage = self::DEFAULT_PER_PAGE,
        string $sortBy = 'published_at',
    ): array {
        $allowedSort = ['published_at', 'view_count', 'created_at'];
        $orderColumn = \in_array($sortBy, $allowedSort, true) ? $sortBy : 'published_at';

        $countStmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM articles a INNER JOIN article_category ac ON ac.article_id = a.id AND ac.category_id = ?'
        );
        $countStmt->execute([$categoryId]);
        $total = (int) $countStmt->fetchColumn();

        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare(
            "SELECT a.id, a.slug, a.image, a.name, a.description, a.text, a.view_count, a.published_at, a.created_at
             FROM articles a
             INNER JOIN article_category ac ON ac.article_id = a.id AND ac.category_id = ?
             ORDER BY a.{$orderColumn} DESC
             LIMIT {$perPage} OFFSET {$offset}"
        );
        $stmt->execute([$categoryId]);
        $items = $this->fetchAllFromStatement($stmt);

        return ['items' => $items, 'total' => $total];
    }

    public function getById(int $id): ?ArticleDto
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, slug, image, name, description, text, view_count, published_at, created_at
             FROM articles WHERE id = ? LIMIT 1'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $categoryRows = $this->fetchCategoryRowsForArticle($id);
        return $this->mapper->fromRow($row, $categoryRows);
    }

    public function incrementViewCount(int $id): void
    {
        $stmt = $this->pdo->prepare('UPDATE articles SET view_count = view_count + 1 WHERE id = ?');
        $stmt->execute([$id]);
    }

    /** @return ArticleDto[] */
    public function getSimilar(int $articleId, array $categoryIds, int $limit = 3): array
    {
        if ($categoryIds === []) {
            return [];
        }
        $limit = max(1, min(100, $limit));
        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));
        $params = array_merge($categoryIds, [$articleId]);
        $stmt = $this->pdo->prepare(
            "SELECT a.id, a.slug, a.image, a.name, a.description, a.text, a.view_count, a.published_at, a.created_at
             FROM articles a
             INNER JOIN article_category ac ON ac.article_id = a.id AND ac.category_id IN ({$placeholders})
             WHERE a.id != ?
             GROUP BY a.id
             ORDER BY COUNT(ac.category_id) DESC, a.published_at DESC
             LIMIT {$limit}"
        );
        $stmt->execute($params);
        return $this->fetchAllFromStatement($stmt);
    }

    /** @return array<int, array<string, mixed>> */
    private function fetchCategoryRowsForArticle(int $articleId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT c.id, c.slug, c.name, c.description
             FROM categories c
             INNER JOIN article_category ac ON ac.category_id = c.id AND ac.article_id = ?'
        );
        $stmt->execute([$articleId]);
        $rows = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }
        return $rows;
    }

    /** @return ArticleDto[] */
    private function fetchAllFromStatement(\PDOStatement $stmt): array
    {
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapper->fromRow($row, []);
        }
        return $result;
    }
}
