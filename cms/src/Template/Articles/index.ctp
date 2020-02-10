<!-- File: src/Template/Articles.index.ctp -->
<h1>Articles</h1>
    <?php if($loggedIn): ?>
        <?= $this->Html->link('Add Article', ['action' => 'add']) ?>
    <?php endif; ?>
<table>
    <tr>
        <th>Title</th>
        <th>Blurb</th>
        <?php if($loggedIn): ?>
            <?php if(in_array($loggedIn->role_id, [1,2])): ?>
                <th>Created</th>
            <?php endif ?>
            <th>Author</th>
            <th>Actions</th>
        <?php endif; ?>
    </tr>
    <!-- This is where we'll iterate through our $articles query object, printing out arcticle info -->
    <?php foreach($articles as $article): ?>
    <tr>
        <td>
            <?php if($loggedIn): ?>
                <?= $this->Html->link(h($article->title), ['action'=> "view", $article->slug]) ?>
            <?php else: ?>
                <?= h($article->title) ?>
            <?php endif; ?> 
        </td>
        <td>
            <?= h($article->blurb) ?>
        </td>
        <?php  if($loggedIn): ?>
            <?php if(in_array($loggedIn->role_id, [1,2])): ?>
                <td>
                    <?= $article->created->format(DATE_RFC850) ?>
                </td>
            <?php endif; ?>
            <td>
                <?= h($article->user->fname) ?>
            </td>
            <td>
                <?= $this->Html->link("View", ['action'=> "view", $article->slug]) ?>
                <?php if($article->user_id == $loggedIn->id || in_array($loggedIn->role_id, [1,2])): ?>
                <!-- Admin, editors, and writers of the article -->
                    <?= $this->Html->link("Edit", ["action" => "edit", $article->slug]) ?>
                    <?php if($article->user_id == $loggedIn->id || $loggedIn->role_id == 1): ?>
                    <!-- Admins and writers of the article can delete -->
                        <?= $this->Form->postLink(
                            "Delete", 
                            ["action"=> "delete", $article->slug], 
                            ["confirm"=> "Are you certain?"]) ?>
                    <?php endif; ?>  <!-- delete action -->
                <?php endif; ?> <!-- end admin, editor and writer of article -->
            </td>
        <?php endif; ?>  <!-- end if logged in  -->
    </tr>
    <?php endforeach; ?>
</table>