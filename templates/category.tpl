{include file='layout/header.tpl'}
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

            <ul class="article-list">
                {foreach $articles as $article}
                    <li class="article-preview">
                        {if $article->image}
                            <a href="/article/{$article->slug}" class="article-preview-image">
                                <img src="/{$article->image}" alt="{$article->name|escape}" loading="lazy">
                            </a>
                        {/if}
                        <a href="/article/{$article->slug}" class="article-preview-title">{$article->name}</a>
                        {if $article->description}
                            <p class="article-preview-description">{$article->description|escape}</p>
                        {/if}
                        {if $article->text}
                            <p class="article-preview-excerpt">{$article->text|truncate:200:"..."|escape}</p>
                        {/if}
                        <a href="/article/{$article->slug}" class="article-preview-more">Подробнее</a>
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
{include file='layout/footer.tpl'}
