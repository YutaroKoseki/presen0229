<?php
//============================================================
// ajaxによる「参考になった」切り替え処理ページ
//============================================================


require_once ('function.php');
session_start();

if (!empty($_POST['r_id']) && !empty($_POST['u_id'])) {
    $r_id = $_POST['r_id'];
    $u_id = $_POST['u_id'];

    // toggleHelpful実行後の状態を取得
    $isHelpful = toggleHelpful($r_id, $u_id);

    // 現在の状態（参考になったかどうか）もレスポンスに含める
    $response = ['success' => true, 'isHelpful' => $isHelpful];
}

echo json_encode($response);