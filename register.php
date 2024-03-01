<?php
require_once ('head.php');
require_once ('header.php');

//　ログイン処理
if(!empty($_POST['register'])){
    $name  = (!empty($_POST['name']))?  $_POST['name'] : '';
    $email = (!empty($_POST['email']))? $_POST['email'] : '';
    $pass  = (!empty($_POST['pass']))?  $_POST['pass'] : '';
    $pass_re  = (!empty($_POST['pass_re']))?  $_POST['pass_re'] : '';

    // バリデーション
    validRequire($name, 'name');
    validRequire($email, 'email');
    validExist($name, $email, 'email');

    validMaxLen($name, 'name');
    validEmail($email, 'email');

    validPass($pass, 'pass');

    // パスワードを再入力と確認
    if($pass !== $pass_re){
        $err_msg = ERR06;
    }


    // エラーが無い場合
    if(empty($err_msg)){

        //パスワードをハッシュ化
        $pass = password_hash($pass, PASSWORD_DEFAULT);
        // ユーザー登録処理
        addUser($name, $email, $pass);
    }
}

?>

<body>
<div class="c-view p-register">
    <h2 class="c-title p-register__title">新規登録</h2>
    <div class="p-container">
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="c-form p-register">

            <label for="name" class="c-label p-register__label">名前：</label>
            <input id="name" type="text" name="name" placeholder="ユーザー名を入力してください" required class="c-input c-form--input p-register__pass">
            <p class="c-error"><?php echo getErrMsg('name') ;?></p>

            <label for="email" class="c-label p-register__label">Email：</label>
            <input id="email" type="email" name="email" placeholder="メールアドレスを入力してください" required class="c-input c-form--input p-register__email">
            <p class="c-error"><?php echo getErrMsg('email') ;?></p>

            <label for="pass" class="c-label p-register__label">パスワード</label>
            <input id="pass" type="password" name="pass" placeholder="半角英数字8文字以上" required class="c-input c-form--input p-register__pass">
            <p class="c-error"><?php echo getErrMsg('pass') ;?></p>

            <label for="pass_re" class="c-label p-register__label">パスワード（再入力）</label>
            <input id="pass_re" type="password" name="pass_re" placeholder="パスワードを再入力してください" required class="c-input c-form--input p-register__pass">
            <p class="c-error"><?php echo getErrMsg('pass_re') ;?></p>

            <input type="submit" value="登録する" name="register" class="c-input c-submit p-register__submit">
        </form>
    </div>

</div>
<?php require_once ('footer.php');?>


