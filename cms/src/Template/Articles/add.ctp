<!-- File: src/Template/Articles/add.ctp -->

<h1>Add Article</h1>
<?php 
    echo $this->Form->create($article);
    if($loggedIn->role_id == 1):
?> 
        <!-- this hidden input is to give js file the default value for the checkbox -->
        <input type="hidden" id="loggedUserId" value=<?= $loggedIn->id ?> >
        <figure>
            <figcaption>Who are you adding this article for?</figcaption>

            <input type="radio" class = "addOther" id="myself" name="forWho" value="myself" checked="checked"><label for="myself">Myself</label>
            <input type="radio" class = "addOther" id="other" name="forWho" value="other"> <label for="myself">Another User</label>

        </figure>
        <figure id="addForOthers">
            <figcaption>Who is the other user?</figcaption>
        
            <?php echo $this->Form->control('user_id', ['options' => $users, "label" => "", 'id' => 'otherList', "value" => $loggedIn->id]);?>
        
        </figure>
<?php
    else:
    // echo $this->Form->control('roles_id', ['options' => $roles]);
        echo $this->Form->control("user_id", ["type"=>"hidden", "value"=>$loggedIn->id]);
    endif;
    
    echo $this->Form->control("title");
    echo $this->Form->control("body", ["rows"=> "3"]);
    echo $this->Form->control('tag_string', ['type' => 'text']);
    echo $this->Form->button(__("Save Article"));
    echo $this->Form->end();
?>
<?= $this->Html->script('articlesAddCheck.js') ?>