<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <h1>Placeholder for future menu</h1>
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('Log in'), ['controller' => 'Users', 'action' => 'login']) ?></li>
    </ul>
</nav>
<p>Welcome to Seth's CakePHP Sample CMS. </p>
<?php if($isLoggedIn): ?>
    <p>You are currently logged in as <?= $greetingName ?> with (Later add role of person) access.</p>
<?php else: ?>
    <p>You are not currently logged in. Please <?= $this->Html->link(__('log in'), ['controller' => 'Users', 'action' => 'login']) ?> or <?= $this->Html->link(__('sign up'), ['controller' => 'Users', 'action' => 'add']) ?> to start contributing articles</p>
<?php endif; ?>
<p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quam corporis, maxime veritatis id pariatur unde recusandae beatae culpa cum numquam sit ex ipsa expedita repudiandae aperiam soluta ullam blanditiis. Debitis.</p>
<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum nesciunt veritatis quis exercitationem, amet similique distinctio reiciendis consequuntur inventore placeat quos eveniet fuga dolorem dolores corporis eaque, ullam harum nihil?</p>