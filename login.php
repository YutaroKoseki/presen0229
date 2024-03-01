<?php

require_once ('head.php');
require_once ('header.php');

//　ログイン処理
if(!empty($_POST['login'])){
    $email = (!empty($_POST['email']))? $_POST['email'] : '';
    $pass = (!empty($_POST['pass']))? $_POST['pass'] : '';
    
    // バリデーション
    validRequire($email, 'email');
    validPass($pass, 'pass');


    // エラーが無い場合
    if(empty($err_msg)){
        // 例外処理
        try{
            // ユーザー情報をDBから取得
            $dbh = dbConnect();
            $sql = 'SELECT * FROM users WHERE email = :email';
            $data = [':email'=> $email];

            $stmt = query($dbh, $sql, $data);

            $result = $stmt->fetch();

            if($result && empty($err_msg)){

                passMatch($pass, $result['password'], 'pass');

                // DBから取得成功かつ、パスワードが一致している場合)
                if(empty($err_msg)){
                    $_SESSION['msg'] = SUC01;
                    // セッションにログイン情報を置いとく。
                    $_SESSION['login'] = true;
                    $_SESSION['user_id'] = $result['id'];
                    header("Location: mypage.php");
                    exit;
                }
            }

        }catch(PDOException $e){
            $err_msg['common'] = ERR08;
            error_log('ログインエラー： '.$e->getMessage());
        }

    }
}

?>

<body>
<div class="c-view p-login">
    <h2 class="c-title p-login__title">ログイン</h2>
    <div class="p-container">
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="c-form p-login">
            <label for="email" class="c-label p-login__label">Email：</label>
            <input id="email" type="email" name="email" placeholder="メールアドレスを入力してください" required class="c-input c-form--input p-login__email" value="<?php echo getFormData('email')?>">
            <p class="c-error"><?php echo getErrMsg('email');?></p>

            <label for="pass" class="c-label p-login__label">パスワード</label>
            <input id="pass" type="password" name="pass" placeholder="パスワードを入力してください" required class="c-input c-form--input p-login__pass">
            <p class="c-error"><?php echo getErrMsg('pass') ;?></p>

            <input type="submit" value="ログインする" name="login" class="c-input c-submit p-login__submit">
        </form>
    </div>

</div>
<?php require_once ('footer.php');?>


