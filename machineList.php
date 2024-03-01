<?php
// HOMEに展開する用の台情報一覧ページ

require_once ('head.php');
require_once ('header.php');
$machineList = getMachineList();
?>

<div class="p-machineList c-view">
    <?php foreach ($machineList as $machine):?>
    <?php $category = ($machine['category'] === 1)? 'パチンコ' : 'スロット'; ?>
        <div class="p-machine">
            <h3 class="p-machine__title"><?php echo $machine['name'].'('. $category. ')'?></h3>
            <img src="<?php echo $machine['thumbnail']?>" class="p-machine__image">
            <div class="p-machine__link">
                <a href="machineDetail.php?machine_id=<?php echo $machine['id']?>" class="p-machine__link">レビューを見る</a>
            </div>
        </div>
    <?php endforeach;?>
</div>

<?php require_once ('footer.php'); ?>