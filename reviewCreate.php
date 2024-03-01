<?php

require_once ('head.php');
require_once ('header.php');

// ログインユーザーじゃない場合はログインページにリダイレクト
if(empty($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

// GETパラメータから台のIDを取得
$m_id = isset($_GET['machine_id']) ? $_GET['machine_id'] : '';


if(!empty($_POST['addReview'])){

    $m_id = isset($_POST['machine_id']) ? $_POST['machine_id'] : '';
    $u_id = isset($_SESSION['user_id'])? $_SESSION['user_id'] : '';

    if(!empty($m_id) && !empty($u_id)){
        $content = $_POST['content'];
        $point = $_POST['point'];

        $result = addReview($u_id, $m_id, $content, $point);

        if($result){
            header('Location: machineList.php');
            exit;
        }
    }
}

?>

<div class="p-reviewCreate c-view">
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="c-form p-addReview">
        <input type="hidden" name="machine_id" value="<?php echo sanitize($m_id); ?>">

        <label for="point" class="p-addReview__label">点数（5点満点中）</label>
        <select name="point" id="point" class="p-addReview__select">
            <option value="1">1点</option>
            <option value="2">2点</option>
            <option value="3">3点</option>
            <option value="4">4点</option>
            <option value="5">5点</option>
        </select>

        <label for="content" class="p-addReview__label">内容</label>
        <textarea name="content" id="content" class="p-addReview__textarea" cols="30" rows="10" placeholder="レビュー内容をここに記入してください"></textarea>

        <input type="submit" class="c-submit p-addReview__submit" name="addReview" value="レビューする">
    </form>
</div>

<?php require_once ('footer.php');?>