<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\CategoryDto;
use App\Mapper\CategoryMapper;
use PDO;

final class CategoryRepository
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly CategoryMapper $mapper,
    ) {
    }

    public function getBySlug(string $slug): ?CategoryDto
    {
        $stmt = $this->pdo->prepare('SELECT id, slug, name, description FROM categories WHERE slug = ? LIMIT 1');
        $stmt->execute([$slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapper->fromRow($row) : null;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT id, slug, name, description FROM categories ORDER BY name');
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapper->fromRow($row);
        }
        return $result;
    }

    public function getById(int $id): ?CategoryDto
    {
        $stmt = $this->pdo->prepare('SELECT id, slug, name, description FROM categories WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapper->fromRow($row) : null;
    }

    /** @return CategoryDto[] */
    public function getByIds(array $ids): array
    {
        if ($ids === []) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->pdo->prepare("SELECT id, slug, name, description FROM categories WHERE id IN ($placeholders) ORDER BY name");
        $stmt->execute(array_values($ids));
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapper->fromRow($row);
        }
        return $result;
    }

    /** @return CategoryDto[] */
    public function getOnlyWithArticles(): array
    {
        $stmt = $this->pdo->query(
            'SELECT c.id, c.slug, c.name, c.description FROM categories c
             INNER JOIN article_category ac ON ac.category_id = c.id
             GROUP BY c.id, c.slug, c.name, c.description ORDER BY c.name'
        );
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapper->fromRow($row);
        }
        return $result;
    }
}
