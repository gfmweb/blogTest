<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Блог</title>
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <header>
        <h1><a href="/">Блог</a></h1>
    </header>
    <main>
        {foreach $categoriesWithArticles as $item}
            <section>
                <h2>{$item.category->name}</h2>
                <p>{$item.category->description}</p>
                <ul>
                    {foreach $item.articles as $article}
                        <li>
                            <a href="/article/{$article->slug}">{$article->name}</a>
                        </li>
                    {/foreach}
                </ul>
                <p><a href="/category/{$item.category->slug}">Все статьи</a></p>
            </section>
        {/foreach}
    </main>
</body>
</html>
