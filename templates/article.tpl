{include file='layout/header.tpl'}
        <article>
            <h2>{$article->name}</h2>
            {if $article->image}
                <div class="article-image">
                    <img src="/{$article->image}" alt="{$article->name|escape}">
                </div>
            {/if}
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
            <section class="similar-articles">
                <h3>Похожие статьи</h3>
                <ul class="article-list">
                    {foreach $similar as $sim}
                        <li class="article-preview">
                            {if $sim->image}
                                <a href="/article/{$sim->slug}" class="article-preview-image">
                                    <img src="/{$sim->image}" alt="{$sim->name|escape}" loading="lazy">
                                </a>
                            {/if}
                            <a href="/article/{$sim->slug}" class="article-preview-title">{$sim->name}</a>
                            {if $sim->description}
                                <p class="article-preview-description">{$sim->description|escape}</p>
                            {/if}
                            {if $sim->text}
                                <p class="article-preview-excerpt">{$sim->text|truncate:200:"..."|escape}</p>
                            {/if}
                            <a href="/article/{$sim->slug}" class="article-preview-more">Подробнее</a>
                        </li>
                    {/foreach}
                </ul>
            </section>
        {/if}
{include file='layout/footer.tpl'}
