<?php

namespace Anax\View;

$users = isset($users) ? $users : null;

?>

<?php if (!$users) : ?>
    <p>There are no results to show.</p>
<?php
    return;
endif;
?>
<div class="view-all-users-home">
    <h1 class="main-title">Most active users</h1>
    <ul class="view-all-user-home">
        <?php foreach ($users as $user) : ?>
                <li>
                    <img class="gravatar" src="<?php echo $user->gravatar; ?>" alt="" /></<img>
                    <a href="<?= url("user/showprofile/{$user->username}"); ?>">
                        <p><?= $user->username ?></p>
                    </a>
                    <p class="text-xs"><?= $user->rankTitle ?> /
                    Score: <b><?= $user->score ?></b></p>
                </li>
        <?php endforeach; ?>
    </ul>
    <p class="view-all-user-btn">
        <a href="<?= url("user"); ?>">Go to users</a>
    </p>
</div>
