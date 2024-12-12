<?php
    // имена парсеров
    $namesPlatform = json_decode(file_get_contents(__DIR__.'/../../../PHP/api/names.json'),true);
    $sample = $GLOBALS['sample'];
?>
<div class="content">

    <!-- PLATFORM -->
    <div class="text-block" id="block-platform">

          <?=str_replace(['{clinic}','{name}', '{bonus}'],[$GLOBALS['clinic'], $GLOBALS['name'], $GLOBALS['bonus']], $sample['TextPositive']['header']);?>
          <?=str_replace(['{clinic}','{name}', '{bonus}'],[$GLOBALS['clinic'], $GLOBALS['name'], $GLOBALS['bonus']], $sample['TextPositive']['body']);?>
          <p class="type-input"><?=$GLOBALS['bonus'];?></p>
          <?=str_replace(['{clinic}','{name}', '{bonus}'],[$GLOBALS['clinic'], $GLOBALS['name'], $GLOBALS['bonus']], $sample['TextPositive']['dop']);?>
       

        <div class="platform">
            <?php foreach ($GLOBALS['platform'] as $plName) : ?>
                <p class="type-input" data="<?= $plName; ?>" dataname="<?= $namesPlatform[$plName]; ?>" id="platform-button">
                    <img src="/view/images/search-<?= $plName; ?>.png" />
                    <b style="margin: 0px 10px; padding: 6px 0px 0px 0px;"><?= $namesPlatform[$plName]; ?></b>
                </p>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- CONFIRM -->
    <div class="text-block disabled" id="block-confirm">

        <?=str_replace(['{clinic}','{name}','{bonus}'],[$GLOBALS['clinic'], $GLOBALS['name'], $GLOBALS['bonus']], $sample['TextPositiveConfirm']['header']);?>
        <?=str_replace(['{clinic}','{name}', '{bonus}'],[$GLOBALS['clinic'], $GLOBALS['name'], $GLOBALS['bonus']], $sample['TextPositiveConfirm']['body']);?>
        <h3 style="margin-bottom: 0px;">Начислим</h3>
        <p class="type-input"><?=$GLOBALS['bonus'];?></p>

        <?=str_replace(['{clinic}','{name}'],[$GLOBALS['clinic'], $GLOBALS['name'], $GLOBALS['bonus']], $sample['TextPositiveConfirm']['dop']);?>
        <p style="margin-bottom: 0px;">Скопировать имя</p>
        <p class="type-input" ><?= $GLOBALS['name']; ?></p>
        <?=str_replace(['{clinic}','{name}', '{bonus}'],[$GLOBALS['clinic'], $GLOBALS['name'], $GLOBALS['bonus']], $sample['TextPositiveConfirm']['fut']);?>
        <button id="button-confirm" style="margin-top: 50px;">Понятно, перейти на сайт </button>
    </div>
</div>
<script src="/view/static/stJS/positive.js"></script>