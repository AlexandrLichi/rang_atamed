<?php

use Pet\ErListen\ErListen;

// header("Content-type: text/plain;");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error <?= $code = ErListen::code(); ?></title>
</head>
<style>
    * {
        margin: 0px;
        padding: 0px;
        box-sizing: border-box;
    }

    body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background: #d5d5f3;
        color: #3b3b76;
        /* max-width: 50vw; */
        text-wrap: balance;
    }

    span {
        width: 50%;
        height: 1px;
        margin: 10px 0px;
        background: #3b3b76;
        
    }

    pre {
    
        /* justify-content: center; */
      
        box-sizing: border-box;
        background: #1a1a1b;
        width: 1000px;
        color: #4fb931;
        text-wrap: balance;
    }
</style>

<body>
    <?php if (!isset($dump)) : ?>
        <h1><?= $code;  ?></h1>
        <span></span>
        <h2><?= ErListen::text($code); ?></h2>
    <?php else : ?>
        <pre>
        <?= print_r($dump, true); ?>
    </pre>
    <?php endif ?>
</body>

</html>