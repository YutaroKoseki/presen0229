<?php

require_once ('head.php');
require_once ('header.php');

// authチェック
authCheck();

// ユーザー情報取得
$user_id = $_SESSION['user_id'];
$user = getUser($user_id);

// レビュー情報取得
$reviewList = getUserReviews($user_id);

// レビューの削除
if(!empty($_POST['delete'])){
    $r_id = (!empty($_POST['r_id']))? $_POST['r_id'] : '';
    $u_id = (!empty($_POST['u_id']))? $_POST['u_id'] : '';

    deleteReview($r_id, $u_id);
}

?>

<div class="c-view p-mypage">

    <div class="p-user">
        <p class="p-user__name"><?php echo $user['name']?> さん</p>
        <div class="p-user__avatar">
            <img src="<?php echo ($user['avatar'] === null)? 'uploads/default_avatar.png' : $user['avatar']; ?>" class="c-image p-user__avatar--item">
        </div>
    </div>

    <div class="p-userReviews">
        <h2 class="p-userReviews__title">投稿したレビュー一覧</h2>

        <?php if(!empty($reviewList)): ?>
            <div class="p-userReviews__wrap">
                <?php foreach ($reviewList as $review) :?>

                    <div class="p-userReviews__contents">
                        <div class="p-userReviews__machine">
                            <img src="<?php echo (!empty($review['thumbnail']))? $review['thumbnail']: 'default_thumbnail.png'?>" class="p-userReviews__machine--thumbnail">
                            <p class="p-userReviews__machine--name"><?php echo $review['name']; ?></p>
                        </div>

                        <div class="p-userReviews__point">
                            <?php
                            $maxPoints = 5; // 満点の点数
                            $awardedPoints = $review['point']; // 実際の点数

                            for ($i = 0; $i < $maxPoints; $i++):
                                if ($i < $awardedPoints):
                                    // 実際の点数分だけ実星を表示
                                    echo '<i class="fa-solid fa-star"></i>';
                                else:
                                    // 残りは空星で表示
                                    echo '<i class="fa-regular fa-star"></i>';
                                endif;
                            endfor;
                            ?>
                        </div>

                        <div class="p-userReviews__about">
                            <p class="p-userReviews__about--text"><?php echo sanitize($review['content'])?></p>
                        </div>

                        <form action="mypage.php" method="post" class="p-userReviews__delete">
                            <input type="hidden" name="r_id" value="<?php echo $review['id']; ?>">
                            <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id']; ?>">
                            <input class="c-submit p-userReviews__delete--submit" type="submit" name="delete" value="レビューを削除" onclick="return confirm('本当に削除しますか？');">
                        </form>
                    </div>

                <?php endforeach;?>
            </div>
        <?php else:?>
            <h3 class="p-userReviews__subtitile">まだ投稿したレビューはありません</h3>
        <?php endif; ?>
    </div>
</div>



<?php
require_once ('footer.php');
