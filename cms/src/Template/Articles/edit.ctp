<!-- File: src/Template/Articles/edit.ctp -->

<h1>Edit Article</h1>
<?php if($loggedIn->id != $article->user_id): ?>
    <h3>You are editing someone else's article</h3>
    <h4>Please be careful!</h4>
<?php endif; ?>
<?php 
    echo $this->Form->create($article);
    // echo $this->Form->control("user_id", ["type"=>"hidden"]);
    echo $this->Form->control("title");
    echo $this->Form->control("body", ["rows"=> "3"]);
    echo $this->Form->control('tag_string', ['type' => 'text']);
    echo $this->Form->button(__("Save Article"));
    echo $this->Form->end();
?>