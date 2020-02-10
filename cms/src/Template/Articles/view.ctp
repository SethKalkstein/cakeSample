<!-- File: src/Template/Articles/view.ctp -->

<p><?= $this->Html->link('All Articles', ['action'=> 'index']) ?></p>
<h1><?= h($article->title) ?></h1>
<h3>Content:</h3>
<p><?= h($article->body) ?></p>

<?php $tagCount = count($article->tags);
    if($tagCount != 0): ?>
    <figure>
        <figcaption><b>Associated Tag<?php echo ($tagCount > 1 ? "s" : ""); ?>:</b></figcaption>
        <ul>
            <?php foreach($article->tags as $tag): ?>
                <li><?= $this->Html->link(h($tag->title), ['controller' => 'Tags', 'action'=> 'view', $tag->id]) ?></li>
            <?php endforeach; ?>
        </ul>
    </figure>
<?php endif; ?>

<?php if($article->user_id == $loggedIn->id || in_array($loggedIn->role_id, [1, 2] )): ?>
    <p>
        <?= $this->Html->link('Edit', ['action' => 'edit', $article->slug]) ?>
        <?php if($article->user_id == $loggedIn->id || $loggedIn->role_id == 1): ?>
            <?= $this->Form->postLink(
            "Delete", 
                ["action"=> "delete", $article->slug], 
                ["confirm"=> "Are you certain?"]) ?>
        <?php endif; ?>
    </p>
<?php endif; ?>

<p><small>Created: <?= $article->created->format(DATE_RFC850)?>, 
    created by: <?= h($article->user->fname) ?> <?= h($article->user->lname) ?>.</small></p>