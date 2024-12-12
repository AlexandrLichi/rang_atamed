<?php
$sample = $GLOBALS['sample'];

use Pet\Middleware\MiddlewareCSRF;
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="token-csrf" value="<?= MiddlewareCSRF::getCSFR(); ?>" />
    <link type="Image/x-icon" href="<?= env('WEBSITEDEV')."/view/images/icon_review.png"?>" rel="icon">
    <link href="/view/static/stCss/style.css" rel="stylesheet">
    <script src="/view/static/stJS/fetch.js"></script>
    <title>Оставить отзыв</title>
    
    <style>
        *{ color:<?=$sample['style']['color'];?>}

        content{
            background-color:<?=$sample['style']['background-color'];?>;
            color:<?=$sample['style']['color'];?>
        }

        h3,p,b{ color:<?=$sample['style']['color'];?> }

        button{
            color: <?=$sample['button']['color'];?>;
            background: <?=$sample['button']['background'];?>;
            border: <?=$sample['button']['border'];?>;
            border-radius:<?=$sample['button']['border-radius'];?>
        }
       
    </style>

</head>

<body>
    <div class="block">
        <?php
        
         include_once(__DIR__ . '/header.php'); ?>

        <?php

            if ($GLOBALS['star'] == 0) {

                include_once(__DIR__ . '/stars.php');

            } elseif ($GLOBALS['star'] >= $sample['positive']) {

                include_once(__DIR__ . '/positive/positive.php');

            } elseif ($GLOBALS['star'] <  $sample['positive']) {

                include_once(__DIR__ . '/negative/negative.php');
            }
        ?>

        <?php include_once(__DIR__ . '/footer.php'); ?>
    </div>
</body>

</html>