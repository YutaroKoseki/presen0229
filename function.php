<?php

//==========================================================================
//==========================================================================

// DB作成時

//==========================================================================
//==========================================================================

//create table users (id int auto_increment primary key, name varchar(255) not null, email varchar(255) not null, password varchar(255) not null, avatar varchar(255));
//
//create table machines (id int auto_increment primary key, name varchar(255) not null, category int(1) not null, thumbnail varchar(255));
//
//create table reviews (id int auto_increment primary key, user_id int, machine_id int, content text not null, point int default 1, created_at datetime not null, foreign key (user_id) references users(id), foreign key (machine_id) references machines(id));
//
//create table helpful (id int auto_increment primary key, review_id int, user_id int, marked_at datetime not null, foreign key (review_id) references reviews(id), foreign key (user_id) references users(id));


//==========================================================================
//==========================================================================

// ログ

//==========================================================================
//==========================================================================


//ログを取るか
ini_set('log_errors','on');
//ログの出力ファイル
ini_set('error_log','php.log');


//==========================================================================
//==========================================================================

// グローバル変数

//==========================================================================
//==========================================================================

$err_msg = array();


//==========================================================================
//==========================================================================

// 定数

//==========================================================================
//==========================================================================


define('ERR01', '未入力の項目があります');
define('ERR02', '8文字以上必要です');
define('ERR03', '255文字以内で入力してください');
define('ERR04', '半角英数字のみご利用可能です');
define('ERR05', 'ユーザー情報に誤りがあります');
define('ERR06', 'パスワード（再入力）が一致しません');
define('ERR07', 'すでに存在するユーザーです');
define('ERR08', 'DBとの接続でエラーが発生しました');
define('ERR09', 'データの取得に失敗しました');
define('ERR10', '正しいメール形式ではありません');

define('SUC01', 'ログインしました！');
define('SUC02', 'レビューを投稿しました！');
define('SUC03', 'レビューを削除しました');
define('SUC04', '情報を更新しました');
define('SUC05', '正常に処理されました');



//==========================================================================
//==========================================================================

// DB接続関連

//==========================================================================
//==========================================================================


// DB接続関数
function dbConnect(){
    // PDOでDBに接続するための準備
    $dsn = 'mysql:dbname=pati;host=localhost;charset=utf8;unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock';
    $user = 'root';
    $pass = 'root';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // エラーモードの設定
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // フェッチモードのデフォ
        // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    ];

    $db = new PDO($dsn, $user, $pass, $options);
    return $db;
}

// クエリ実行関数
function query($dbh, $sql, $data){
    global $err_msg;
    // DBへ接続、SQL実行のための準備
    $stmt = $dbh->prepare($sql);

    // SQLの実行
    if(!$stmt->execute($data)){
        $err_msg['common'] = ERR08;
        return false;
    }

    return $stmt;
}

//-----------------------------------------------------------
// ユーザー関連
//-----------------------------------------------------------

// ユーザー登録
function addUser($name, $email, $password){
    global $err_msg;

    try{
        $dbh = dbConnect();
        $sql = 'INSERT INTO users (name, email, password) VALUES (:name, :email, :pass)';
        $data = [
            ':name'  => $name,
            ':email' => $email,
            ':pass'  => $password
        ];

        $stmt = query($dbh, $sql, $data);


        if($stmt){
            // 成功した場合はマイページにリダイレクト
            $_SESSION['msg'] = SUC01;
            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $dbh->lastInsertId();
            header('Location: mypage.php');
            exit;

        }else{
            // 失敗した場合
            $err_msg['common'] = ERR08;
        }


    }catch(PDOException $e){
        $err_msg['common'] = ERR08;
        error_log('addUser()エラー: '. $e->getMessage());
    }
}

// ユーザー情報取得
function getUser($u_id){
    global $err_msg;

    try{
        $dbh = dbConnect();
        $sql = 'SELECT * FROM users WHERE id = :u_id';
        $data = [':u_id' => $u_id];

        $stmt = query($dbh, $sql, $data);

        // ユーザー情報をフェッチしてリターン
        $result = $stmt->fetch();
        return $result;

    }catch (PDOException $e){
        $err_msg['common'] = ERR08;
        error_log('getUser() :'. $e->getMessage());
    }
}

// ユーザー情報更新
function updateUser($u_id, $name, $email, $avatar){
    global $err_msg;

    try{
        $dbh = dbConnect();
        $sql = 'UPDATE users SET name = :name, email = :email, avatar = :avatar WHERE id = :u_id';
        $data = [
            ':name'   => $name,
            ':email'  => $email,
            ':avatar' => $avatar,
            ':u_id'   => $u_id
        ];

        $stmt = query($dbh, $sql, $data);

        if($stmt){
            // 成功した場合はマイページにリダイレクト
            $_SESSION['msg'] = SUC04;
            header('Location: mypage.php');
            exit;

        }else{
            $err_msg['common'] = ERR08;
            return false;
        }

    }catch (PDOException $e){
        $err_msg['common'] = ERR08;
        error_log('updateUser()エラー: '. $e->getMessage());
    }
}

// ユーザー情報削除
function deleteUser($u_id){
    global $err_msg;

    try{
        $dbh = dbConnect();
        $sql = 'DELETE FROM users WHERE id = :id';
        $data = [
            ':id' => $u_id
        ];

        $stmt = query($dbh, $sql, $data);


        if($stmt){
            // 成功した場合はセッションを削除しHOMEへリダイレクト
            destroy();
            $_SESSION['msg'] = SUC05;
            header('Location: login.php');
            exit;

        }else{
            $err_msg['common'] = ERR08;
            return false;
        }


    }catch (PDOException $e){
        $err_msg['common'] = ERR08;
        error_log('userDelete()エラー: '. $e->getMessage());
    }
}

//-----------------------------------------------------------
// 台関連
//-----------------------------------------------------------

// 台情報一覧取得
function getMachineList(){
    try{
        $dbh = dbConnect();
        $sql = 'SELECT * FROM machines';
        $data = [];

        $stmt = query($dbh, $sql, $data);

        $result = $stmt->fetchAll();
        return $result;


    }catch (PDOException $e){
        error_log('getMachineList(): '. $e->getMessage());
    }
}

// 台情報詳細取得
function getMachineDetail($m_id){
    global $err_msg;
    try{
        $dbh = dbConnect();
        $sql = 'SELECT * FROM machines where id = :m_id';
        $data = [
            ':m_id' => $m_id
        ];

        $stmt = query($dbh, $sql, $data);

        $result = $stmt->fetch();
        return $result;


    }catch (PDOException $e){
        $err_msg['common'] = ERR09;
        error_log('getMachine(): '. $e->getMessage());
    }
}


//-----------------------------------------------------------
// レビュー関連
//-----------------------------------------------------------

// ユーザーの投稿したレビュー情報取得
function getUserReviews($u_id){
    global $err_msg;

    try{
        $dbh = dbConnect();
        // レビューと台情報を結合して取得するSQLクエリ
        $sql = 'SELECT reviews.id, reviews.content, reviews.point, reviews.created_at, machines.name, machines.thumbnail 
                FROM reviews 
                JOIN machines ON reviews.machine_id = machines.id 
                WHERE reviews.user_id = :user_id 
                ORDER BY reviews.created_at DESC 
                LIMIT 10';
        $data = [
            ':user_id' => $u_id
        ];

        $stmt = query($dbh, $sql, $data);
        $result = $stmt->fetchAll();

        return $result;

    }catch(PDOException $e){
        $err_msg['common'] = ERR09;
        error_log('getUserReviewsWithMachines: ' . $e->getMessage());
    }
}


// 台に基づくレビュー情報取得:
function getMachineReviews($m_id){
    global $err_msg;

    try{
        $dbh = dbConnect();
        $sql = 'SELECT reviews.id, reviews.content, reviews.point, reviews.created_at, users.avatar, users.name FROM machines join reviews on machines.id = reviews.machine_id join users on reviews.user_id = users.id WHERE reviews.machine_id = :m_id';
        $data = [
            ':m_id' => $m_id
        ];

        $stmt = query($dbh, $sql, $data);
        $result = $stmt->fetchAll();

        return $result;

    }catch(PDOException $e){
        $err_msg['common'] = ERR09;
        error_log('getReviews: '. $e->getMessage());
    }
}

// レビューの新規投稿
function addReview($u_id, $m_id, $content, $point){
    global $err_msg;

    try{
        $dbh = dbConnect();
        $sql = 'INSERT INTO reviews (user_id, machine_id, content, point, created_at) VALUES (:u_id, :m_id, :content, :point, now())';
        $data = [
            ':u_id'    => $u_id,
            ':m_id'    => $m_id,
            ':content' => $content,
            ':point'   => $point
        ];

        $stmt = query($dbh, $sql, $data);

        if($stmt){
            $_SESSION['msg'] = SUC02;
            return true;
        }else{
            //　処理が失敗した場合
            $err_msg['common'] = ERR08;
            return false;
        }


    }catch (PDOException $e){
        $err_msg['common'] = ERR08;
        error_log('addReview()エラー: '. $e->getMessage());
    }
}

// レビュー削除
function deleteReview($r_id, $u_id){
    global $err_msg;

    try{
        $dbh = dbConnect();
        $sql = 'DELETE FROM reviews WHERE id = :r_id AND user_id = :u_id';
        $data = [
            ':r_id' => $r_id,
            ':u_id' => $u_id
        ];

        $stmt = query($dbh, $sql, $data);

        if($stmt){
            // 成功した場合は今いるページにリダイレクト
            $_SESSION['msg'] = SUC05;
            $safeUrl = sanitize($_SERVER['PHP_SELF']);
            header('Location: '. $safeUrl);
            exit;

        }else{
            $err_msg['common'] = ERR08;
            return false;
        }


    }catch (PDOException $e){
        $err_msg['common'] = ERR08;
        error_log('deleteReview()エラー: '. $e->getMessage());
    }
}

//-----------------------------------------------------------
// 参考になった。関連
//-----------------------------------------------------------

// 参考になった情報取得
function getHelpful($r_id, $u_id){
    global $err_msg;

    try{
        $dbh = dbConnect();
        $sql = 'SELECT count(*) FROM helpful WHERE review_id = :r_id AND user_id = :u_id';
        $data = [
            ':r_id' => $r_id,
            ':u_id' => $u_id
        ];

        $stmt = query($dbh, $sql, $data);

        $count = $stmt->fetchColumn();
        return $count > 0;

    }catch(PDOException $e){
        $err_msg['common'] = ERR08;
        error_log('getHelpful()エラー: '. $e->getMessage());
    }
}

// 参考になったのON・OFF切り替え
function toggleHelpful($r_id, $u_id){
    global $err_msg;

    try{
        $dbh = dbConnect();
        $sql = 'SELECT count(*) FROM helpful WHERE review_id = :r_id AND user_id = :u_id';
        $data = [
            ':r_id' => $r_id,
            ':u_id' => $u_id
        ];

        $stmt = query($dbh, $sql, $data);


        if($stmt->fetchColumn() > 0){
            // 存在する場合
            $sql = 'DELETE FROM helpful WHERE review_id = :r_id AND user_id = :u_id';
            $_SESSION['msg'] = '解除しました';
            query($dbh, $sql, $data);
            error_log('toggleHelpful()成功: 削除しました');

        }else{
            // 存在しない場合
            $sql = 'INSERT INTO helpful (review_id, user_id, marked_at) VALUES (:r_id, :u_id, now())';

            $_SESSION['msg'] = '登録しました！';
            query($dbh, $sql, $data);
            error_log('toggleHelpful()成功: 追加しました');
        }

    }catch (PDOException $e){
        $err_msg['common'] = ERR09;
        error_log('toggleHelpful()エラー: '. $e->getMessage());
    }
}


//==========================================================================
//==========================================================================

// セッション・その他

//==========================================================================
//==========================================================================

// サニタイズ
function sanitize($str){
    return htmlspecialchars($str, ENT_QUOTES|ENT_HTML5);
}

// セッションの破棄
function destroy(){
    // セッションが既に開始されているか確認
    if (session_status() === PHP_SESSION_NONE) {
        // セッションが開始されていない場合、開始する
        session_start();
    }
    // セッション変数を全て削除
    $_SESSION = array();
    // セッションクッキーも削除
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    // セッションを破棄
    session_destroy();
}


//フォーム入力保持＆DBデータ表示
function getFormData($str, $flg = true){
    global $dbFormData;

    //メソッドによって分岐
    if($flg){
        //trueならPOST
        $method = $_POST;
    }else{
        //falseならGET
        $method = $_GET;
    }

    //DBデータがある場合
    if(!empty($dbFormData[$str])){
        //エラーがある場合
        if(!empty($err_msg[$str])){
            //フォーム入力がある場合
            if(isset($method[$str])){
                return sanitize($method[$str]);
            }else{
                return sanitize($dbFormData[$str]);
            }

            //エラーが無い場合
        }else{
            //DBとフォームの情報が違う場合はフォームの情報を優先
            if(isset($method[$str]) && $method[$str] !== $dbFormData[$str]){
                return sanitize($method[$str]);
            }else{
                return sanitize($dbFormData[$str]);
            }
        }

    }else{
        //DBデータがなく、フォーム入力がある場合
        if(isset($method[$str])){
            return sanitize($method[$str]);
        }
    }
}


// セッションの一時利用
function sessionFlash($key){
    if(!empty($_SESSION[$key])){
        // データを一時的に変数に保存し、セッションは空に
        $data = $_SESSION[$key];
        unset($_SESSION[$key]);

        return $data;
    }
}



// authチェック
function authCheck(){
    if(!empty($_SESSION['login'])){

        if(basename($_SERVER['PHP_SELF']) === 'login.php'){
            header('Location:mypage.php');
        }

    }else{
        if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
            header('Location:login.php');
        }
    }
}

//エラーメッセージを取得する関数
function getErrMsg($key){
    global $err_msg;
    if(!empty($err_msg[$key])){
        return $err_msg[$key];
    }
}


//==========================================================================
//==========================================================================

// バリデーション

//==========================================================================
//==========================================================================

// 未入力チェック
function validRequire($str, $key){
    global $err_msg;
    if($str == ''){
        $err_msg[$key] = ERR01;
    }
}

//E-mail形式チェック
function validEmail($str,$key){
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",$str)){
        global $err_msg;
        $err_msg[$key] = ERR10;
    }
}

// 最小文字数
function validMinLen($str, $key){
    global $err_msg;

    if(mb_strlen($str) < 8){
        $err_msg[$key] = ERR02;
    }
}

// 最大文字数
function validMaxLen($str, $key){
    global $err_msg;

    if(mb_strlen($str) > 255){
        $err_msg[$key] = ERR03;
    }
}

// 半角英数字
function validHalf($str, $key){
    global $err_msg;

    if(!preg_match("/^[a-zA-Z0-9]+$/",$str)){
        $err_msg[$key] = ERR04;
    }
}

// パスワード一式
function validPass($str, $key){
    validRequire($str, $key);
    //最少・最大文字数チェック
    validMinLen($str, $key);
    validMaxLen($str, $key);
    //半角チェック
    validHalf($str, $key);
}

// パスワードマッチ
function passMatch($str1, $str2, $key){
    global $err_msg;

    if(!password_verify($str1, $str2)){
        $err_msg[$key] = ERR05;
    }
}

// ユーザー重複チェック
function ValidExist($name, $email, $key){
    global $err_msg;

    try{
        $dbh = dbConnect();
        $sql = 'SELECT count(*) as count FROM users WHERE name = :name OR email = :email';
        $data = [
            ':name'  => $name,
            ':email' => $email
        ];

        $stmt = query($dbh, $sql, $data);
        $result = $stmt->fetch();

        // ユーザー情報がヒットする場合はエラー
        if($result && $result['count'] > 0){
            $err_msg[$key] = ERR07;
        }

    }catch (PDOException $e){
        $err_msg['common'] = ERR08;
        error_log('validExist()エラー： '.$e->getMessage());
    }
}


// 画像のアップロード処理
function uploadImg($file,$key){
    global $err_msg;

    //$fileが画像ファイル形式だった場合
    if(isset($file['error']) && is_int($file['error'])){

        //例外処理
        try{
            //画像の各種エラーを設定
            switch($file['error']){
                //OK（正常）
                case UPLOAD_ERR_OK;
                    break;

                //ファイル未選択
                case UPLOAD_ERR_NO_FILE:
                    $err_msg[$key] = 'ファイルが選択されていません';
                    break;

                //サイズオーバー
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $err_msg[$key] = 'ファイルサイズが大きすぎます';
                    break;

                //その他
                default:
                    $err_msg[$key] = 'エラーが発生しました';
            }

            //画像(拡張子)の形式が非対応のときのエラー設定
            //ファイルの形式を判別し、変数に
            $type = @exif_imagetype($file['tmp_name']);

            //それが以下のタイプのどれかでない（非対応の）場合。＊厳密にチェックするため第3引数にtrueをつける
            if(!in_array($type,[IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG],true)){
                $err_msg[$key] = '非対応のファイル形式です';
            }

            //アップロードされたファイルのパス移動、その際のエラーを設定
            //ファイル名を生成し、変数に格納
            $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);

            //パスの移動が失敗した場合
            if(!move_uploaded_file($file['tmp_name'],$path)){
                $err_msg[$key] = 'ファイルの保存に失敗しました';
            }

            //ファイルの権限（パーミッション）を変更
            chmod($path,0644);

            return $path;


        }catch(RuntimeException $e){

            $err_msg[$key] = $e->getMessage();
            error_log('uploadImg()エラー： '. $e->getMessage());
        }
    }
}
