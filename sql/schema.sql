CREATE DATABASE IF NOT EXISTS blog
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE blog;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS article_category;
DROP TABLE IF EXISTS articles;
DROP TABLE IF EXISTS categories;

SET FOREIGN_KEY_CHECKS = 1;


CREATE TABLE categories (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name        VARCHAR(255) NOT NULL COMMENT 'Название категории',
    slug        VARCHAR(255) NOT NULL COMMENT 'ЧПУ-идентификатор категории (уникальный)',
    description TEXT NULL COMMENT 'Описание категории',
    PRIMARY KEY (id),
    UNIQUE KEY uk_categories_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Категории статей';


CREATE TABLE articles (
    id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    slug         VARCHAR(255) NOT NULL COMMENT 'ЧПУ-идентификатор статьи',
    image        VARCHAR(255) NULL COMMENT 'Путь к изображению',
    name         VARCHAR(255) NOT NULL COMMENT 'Название статьи',
    description  VARCHAR(500) NULL COMMENT 'Краткое описание',
    text         TEXT NOT NULL COMMENT 'Текст статьи',
    view_count   INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Количество просмотров',
    published_at DATETIME NULL COMMENT 'Дата и время публикации',
    created_at   DATETIME NOT NULL COMMENT 'Дата и время создания',
    PRIMARY KEY (id),
    UNIQUE KEY uk_articles_slug (slug),
    KEY idx_articles_published_at (published_at),
    KEY idx_articles_view_count (view_count)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Статьи блога';


CREATE TABLE article_category (
    article_id  INT UNSIGNED NOT NULL COMMENT 'ID статьи',
    category_id INT UNSIGNED NOT NULL COMMENT 'ID категории',
    PRIMARY KEY (article_id, category_id),
    KEY idx_article_category_category_id (category_id),
    CONSTRAINT fk_article_category_article FOREIGN KEY (article_id) REFERENCES articles (id) ON DELETE CASCADE,
    CONSTRAINT fk_article_category_category FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Связь статей с категориями';
