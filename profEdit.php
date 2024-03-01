<?php

require_once ('head.php');
require_once ('header.php');


$u_id = (!empty($_GET['userId']))? $_GET['userId'] : '';
$dbFormData = getUser($u_id);

if(!empty($_POST['edit'])){

    // その他の入力値の取得
    $name = $_POST['name'];
    $email = $_POST['email'];
    $u_id = $_POST['userId']; // 隠しフィールドから取得

    // 画像のアップロード処理
    if(!empty($_FILES['avatar']['name'])){
        $avatar = uploadImg($_FILES['avatar'], 'avatar');
    } else {
        // 画像がアップロードされていない場合は、現在の画像パスを保持
        $avatar = getFormData('avatar', $dbFormData);
    }

    // ユーザー情報の更新
    if(empty($err_msg)){
        updateUser($u_id, $name, $email, $avatar);
    }
}



?>

<div class="c-view p-prof">

    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="c-form p-edit" enctype="multipart/form-data">
        <input type="hidden" name="userId" value="<?php echo $u_id?>">

        <div class="p-edit__name">
            <label for="name" class="c-label p-edit__name--label">名前：</label>
            <input id="name" class="c-input c-form--input p-edit__name--input" type="text" name="name" placeholder="ユーザー名" value="<?php echo getFormData('name')?>">
            <p class="p-edit__error c-error"><?php echo getErrMsg('name');?></p>
        </div>

        <div class="p-edit__email">
            <label for="email" class="c-label p-edit__email--label">Email：</label>
            <input id="email" class="c-input c-form--input p-edit__email--input" type="email" name="email" placeholder="メールアドレス" value="<?php echo getFormData('email')?>">
            <p class="p-edit__error c-error"><?php echo getErrMsg('email');?></p>
        </div>

        <div class="p-edit__avatar">
            <label for="avatar" class="c-label p-edit__avatar--label">アバター画像：</label>
            <input id="avatar" class="c-input c-form--input p-edit__avatar--input" type="file" name="avatar" placeholder="画像を選択してください">
            <div class="p-edit__preview">
                <img id="avatarPreview" class="p-edit__preview--image" src="<?php echo getFormData('avatar')?>" alt="アバター画像プレビュー" style="display: none;">
            </div>

            <p class="p-edit__error c-error"><?php echo getErrMsg('avatar')?></p>
        </div>

        <div class="p-edit__submit">
            <input class="c-submit p-edit__submit--input" type="submit" name="edit" value="更新する！">
        </div>
    </form>

    <div class="p-prof__withdraw">
        <a href="withdraw.php?userId=<?php echo $u_id?>" class="p-prof__withdraw--link">退会ページへ</a>
    </div>

</div>

<?php require_once ('footer.php')?>
