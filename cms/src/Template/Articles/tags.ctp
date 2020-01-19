<h1>
    Articles tagged with
    <?= $this->Text->toList(h($tags), "or") ?>
</h1>

<section>
<?php foreach($articles as $article): ?>
    <article>
        <!-- using html helper to create the link instead of an anchor tag -->
        <h4>
            <?= $this->Html->link($article->title,["controller"=>"Articles", "action"=>"view", $article->slug]) ?>
        </h4>
        <span><?= h($article->created) ?></span>
    </article>
<?php endforeach; ?>
</section>