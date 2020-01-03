<?php

namespace Anax\View;

$users = isset($users) ? $users : null;

?>


<?php if (!$users) : ?>
    <div class="text-center">
        <p>Sorry, there were no results for user <b>“ <?= $searchQuery ?> ”</b></p>
        <a href="<?= url("user"); ?>">View all users</a>
    </div>
<?php
    return;
endif;
?>

<div class="view-all-users">
    <!-- <h1 class="main-title">All registred users</h1> -->
<?php echo ($searchQuery) ? "<h1 class='main-title'>Users matching “ <b>$searchQuery</b> “</h1>" : "<h1 class='main-title'>All registred users</h1>" ?>

    <ul class="<?= ($searchQuery) ? "view-all-user result" : "view-all-user" ?>">
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
</div>
