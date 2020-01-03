<?php

namespace Anax\View;

?>

<div class="userprofile">
    <div class="flex">
        <div class="profile-col-left">
            <img class="gravatar" src="<?php echo $userInfo->gravatar; ?>" alt="" />
            <h4 class="username"><?= $userInfo->username ?></h4>
        </div>
        <div class="profile-col-right">
            <div class="userinfo">
                <div class="userdetails">
                    <div class="flex"><h4><?= $userInfo->name ?> </h4><p class="profile-email"><?= $userInfo->email ?></p></div>
                    <p>Rank: <b><?= $userInfo->rankTitle ?></b> </p>
                    <p>Score: <b><?= $userInfo->score ?></b> </p>
                    <p>Posts: <b><?= $userInfo->posts ?></b> </p>
                    <p>Answers: <b><?= $userInfo->answers ?></b> </p>
                    <p>Comments: <b><?= $userInfo->comments ?></b> </p>
                    <p>Votes: <b><?= $userInfo->votes ?></b> </p>
                </div>
            </div>
        </div>
    </div>
</div>


