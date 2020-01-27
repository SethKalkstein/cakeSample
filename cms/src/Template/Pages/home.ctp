<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('Log in'), ['controller' => 'Users', 'action' => 'login']) ?></li>
    </ul>

</nav>
<?php if($isLoggedIn): ?>
    <p>I am in the log and that is awesome!</p>
<?php else: ?>
    <p>I am not logged in, and that is also okay!</p>
<?php endif; ?>