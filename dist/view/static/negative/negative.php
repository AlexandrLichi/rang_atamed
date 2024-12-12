<?php
$sample = $GLOBALS['sample'];

function neg($bool)
{
    if ($bool) {
        return $GLOBALS['path'] == 'NEGATIVE' ? '' : 'disabled';
    } else {
        return $GLOBALS['path'] == 'NEGATIVE' ? 'disabled' : '';
    }
}
?>
<div class="content">
    <!-- CONFIRM -->

    <div class="text-block <?= neg(false); ?>" id="block-confirm">
        <?=str_replace(['{clinic}','{name}', '{bonus}'],[$GLOBALS['clinic'], $GLOBALS['name'], $GLOBALS['bonus']], $sample['TextNegativeConfirm']['header']);?>
        <?=str_replace(['{clinic}','{name}', '{bonus}'],[$GLOBALS['clinic'], $GLOBALS['name'], $GLOBALS['bonus']], $sample['TextNegativeConfirm']['body']);?>
        <?=str_replace(['{clinic}','{name}', '{bonus}'],[$GLOBALS['clinic'], $GLOBALS['name'], $GLOBALS['bonus']], $sample['TextNegativeConfirm']['dop']);?>
        <button id="block-confirm-btn"><?=$sample['button4']['text'];?></button>
    </div>


    <!--BLOCK TEXT -->
    <div class="text-block <?= neg(true); ?>" id="block-text">
         <?=str_replace(['{clinic}','{name}', '{bonus}'],[$GLOBALS['clinic'], $GLOBALS['name'], $GLOBALS['bonus']], $sample['TextNegative']['header']);?>
        <textarea name="negative" <?= neg(false); ?> placeholder="Опишите подробно"><?= neg(false) == 'disabled' ? $GLOBALS['text_negative'] : ''; ?></textarea>
        <button class="<?= neg(false); ?>" id="block-text-btn">Отправить</button>
    </div>

    <!-- FINISH -->
    <div class="text-block disabled" id="block-finish">
        <?=str_replace(['{clinic}','{name}', '{bonus}'],[$GLOBALS['clinic'], $GLOBALS['name'], $GLOBALS['bonus']], $sample['Finish']['header']);?>
        <?=str_replace(['{clinic}','{name}', '{bonus}'],[$GLOBALS['clinic'], $GLOBALS['name'], $GLOBALS['bonus']], $sample['Finish']['body']);?>
        <img class='serd' src="/view/static/images/<?= $sample['Finish']['images']."?".$sample['hash'];?>"></img>
    </div>

</div>
<script src="/view/static/stJS/negative.js"></script>