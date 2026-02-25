{include file='layout/header.tpl'}
        {foreach $categoriesWithArticles as $item}
            <section class="category-block">
                <h2>{$item.category->name}</h2>
                <p>{$item.category->description}</p>
                <ul class="article-list">
                    {foreach $item.articles as $article}
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
                <p><a href="/category/{$item.category->slug}">Все статьи</a></p>
            </section>
        {/foreach}
{include file='layout/footer.tpl'}
