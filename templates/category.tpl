<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$category->name} — Блог</title>
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <header>
        <h1><a href="/">Блог</a></h1>
    </header>
    <main>
        <section>
            <h2>{$category->name}</h2>
            <p>{$category->description}</p>

            <p>Сортировка:
                {if $sortBy == 'published_at'}
                    по дате
                {else}
                    <a href="/category/{$category->slug}?sort=date">по дате</a>
                {/if}
                |
                {if $sortBy == 'view_count'}
                    по просмотрам
                {else}
                    <a href="/category/{$category->slug}?sort=views">по просмотрам</a>
                {/if}
            </p>

            <ul>
                {foreach $articles as $article}
                    <li>
                        <a href="/article/{$article->slug}">{$article->name}</a>
                    </li>
                {/foreach}
            </ul>

            {if $totalPages > 1}
                <nav>
                    {if $page > 1}
                        <a href="/category/{$category->slug}?page={$page - 1}{if $sortBy == 'view_count'}&sort=views{/if}">Назад</a>
                    {/if}
                    Страница {$page} из {$totalPages}
                    {if $page < $totalPages}
                        <a href="/category/{$category->slug}?page={$page + 1}{if $sortBy == 'view_count'}&sort=views{/if}">Вперёд</a>
                    {/if}
                </nav>
            {/if}
        </section>
    </main>
</body>
</html>
