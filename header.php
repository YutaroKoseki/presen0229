<?php
session_start();
require_once ('function.php');

?>

<header class="l-header p-header">
    <h1 class="p-header__title">PHPのテスト</h1>

    <div class="p-header__right">
        <?php if(!empty($_SESSION['login'])): ?>
            <a href="mypage.php" class="p-header__link p-header__link--mypage c-link">マイページ</a>

            <?php if(basename($_SERVER['PHP_SELF']) === 'mypage.php'): ?>
                <a href="home.php" class="p-header__link p-header__link--home c-link">台一覧</a>
                <a href="profEdit.php?userId=<?php echo (!empty($_SESSION['user_id']))? $_SESSION['user_id'] : ''?>" class="p-header__link p-header__link--edit c-link">プロフィール編集</a>
            <?php endif;?>

            <a href="logout.php" class="p-header__link p-header__link--logout c-link">ログアウト</a>
        <?php else:?>

            <a href="login.php" class="p-header__link p-header__link--login c-link">ログイン</a>
            <a href="register.php" class="p-header__link p-header__link--register c-link">会員登録</a>

        <?php endif;?>
    </div>
</header>

<div class="p-message">
    <?php if(!empty($err_msg['common'])):?>
        <p class="p-message__error"><?php echo $err_msg['common']?></p>
    <?php elseif(!empty($_SESSION['msg'])):?>
        <p class="p-message__success"><?php echo sessionFlash('msg')?></p>
    <?php endif;?>
</div>


