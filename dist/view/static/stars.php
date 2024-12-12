<?php $sample = $GLOBALS['sample'];?>
<script>
    let imageStarActive = '<?=$sample['iconStar']['active'] ;?>'
    let imageStarDeactive = '<?=$sample['iconStar']['deactive'] ;?>'
    let hash =  '<?=$sample['hash'];?>'
</script>

<div class="content">
    <div class="text-block">
            <?=str_replace(['{clinic}','{name}'],[$GLOBALS['clinic'], $GLOBALS['name']], $sample['TextStars']['header']);?>
            <?=str_replace(['{clinic}','{name}'],[$GLOBALS['clinic'], $GLOBALS['name']], $sample['TextStars']['body']);?>
    </div>
        <stars>
            <?php for ($i = 0; $i < 5; $i++) : ?>
                <star data="<?= $i; ?>">
                    <img src="/view/static/images/<?=$sample['iconStar']['deactive']."?".$sample['hash']?>">
                </star>
            <?php endfor; ?>
        </stars>
    <div class="text-block">
        <button class="disablend">Подтвердить</button>
    </div>
</div>

<script src="/view/static/stJS/stars.js?<?=$sample['hash'];?>"></script>
