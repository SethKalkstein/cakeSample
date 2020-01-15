<!-- File: src/Template/Articles.index.ctp -->
<h1>Articles</h1>
<table>
    <tr>
        <th>Title</th>
        <th>Created</th>
    </tr>
    <!-- This is where we'll iterate through our $articles query object, printing out arcticle info -->
    <?php foreach($articles as $article): ?>
    <tr>
        <td>
        <?= $this->Html->link($article->title, ['action'=> "view", $article->slug]) ?>
        </td>
        <td>
            <? $article->created->format(DATE_RFC850) ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>