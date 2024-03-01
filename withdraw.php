<?php

require_once ('head.php');
require_once ('header.php');


$u_id = (!empty($_GET['userId']))? $_GET['userId'] : '';

if(!empty($_POST)){

    $u_id = $_POST['user_id'];
    $result = deleteUser($u_id);

    var_dump($result);
}
?>

<div class="c-view p-withdraw">
    <form id="withdraw" action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="c-form p-withdraw__form">
        <input type="hidden" name="user_id" value="<?php echo $u_id?>">
        <p class="c-text p-withdraw__text">退会するとユーザー情報・レビュー情報が全て削除されます。</p>
        <p class="c-text p-withdraw__text">(この操作は取り消しできません)</p>

        <button type="button" id="withdrawButton" class="c-button p-withdraw-button">退会する</button>

        <div id="modal" class="p-modal" style="display:none;">
            <div class="p-modal__content">
                <p class="p-modal__text">本当に退会しますか？</p>
                <button id="confirm" type="submit" class="c-button p-modal__confirm">退会する</button>
                <button id="cancel" type="button" class="c-button p-modal__cancel">キャンセル</button>
            </div>
        </div>

    </form>
</div>

<?php require_once ('footer.php')?>