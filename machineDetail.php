<?php

require_once ('head.php');
require_once ('header.php');

// GETパラメータから台のIDを取得
$m_id = isset($_GET['machine_id']) ? $_GET['machine_id'] : null;

// 台情報取得
$machine = getMachineDetail($m_id);

// 台に紐づくレビュー情報取得
$reviews = getMachineReviews($m_id);

?>

<div class="c-view p-detail">
    <h2 class="p-detail__title"><?php echo $machine['name']?></h2>
    <img src="<?php echo $machine['thumbnail']?>" class="p-detail__thumbnail">

    <div class="p-detail__wrap">
        <?php foreach ($reviews as $review):?>
            <div class="p-machineReview">

                <div class="p-machineReview__user">
                    <img src="<?php echo $review['avatar']?>" class="p-machineReview__user--avatar">
                    <p class="p-machineReview__user--name"><?php echo sanitize($review['name'])?></p>
                </div>

                <div class="p-machineReview__content">
                    <div class="p-machineReview__point">
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

                    <p class="p-machineReview__content--text"><?php echo sanitize($review['content'])?></p>

                    <!-- 参考になったボタン -->
                    <div class="p-machineReview__helpful">
                            <button class="c-button p-machineReview__helpful--button js-helpful-button" data-review-id="<?php echo $review['id']; ?>" data-user-id="<?php echo $_SESSION['user_id']; ?>">
                                <i class="c-icon p-machineReview__helpful--icon fa-regular fa-lightbulb js-toggle-icon"></i>
                                参考になった
                            </button>
                    </div>
                </div>
            </div>

        <?php endforeach;?>
    </div>

    <div class="p-detail__link">
        <a href="reviewCreate.php?machine_id=<?php echo $machine['id']?>" class="c-submit p-detail__link--item">レビューをする</a>
    </div>

</div>

<?php
require_once ('footer.php');
?>
