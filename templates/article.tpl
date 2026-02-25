<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$article->name} — Блог</title>
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    <header>
        <h1><a href="/">Блог</a></h1>
    </header>
    <main>
        <article>
            <h2>{$article->name}</h2>
            {if $article->categories}
                <p>Категории:
                    {foreach $article->categories as $cat name=catLoop}
                        <a href="/category/{$cat->slug}">{$cat->name}</a>{if !$smarty.foreach.catLoop.last}, {/if}
                    {/foreach}
                </p>
            {/if}
            <p>{$article->description}</p>
            <div>{$article->text}</div>
            <p>Просмотров: {$article->viewCount}</p>
        </article>

        {if $similar}
            <section>
                <h3>Похожие статьи</h3>
                <ul>
                    {foreach $similar as $sim}
                        <li><a href="/article/{$sim->slug}">{$sim->name}</a></li>
                    {/foreach}
                </ul>
            </section>
        {/if}
    </main>
</body>
</html>
