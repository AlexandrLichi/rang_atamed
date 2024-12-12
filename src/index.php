<?php
// объявление пространства имен Inertia
use Pet\Inertia\Inertia;
use Pet\Middleware\MiddlewareCSRF;

?>

<!DOCTYPE html />
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="inertia" data="<?= Inertia::$name ?>">
    <meta name="token-csrf" data="<?= MiddlewareCSRF::getCSFR() ?>">
    <link type="Image/x-icon" href="<?= env('WEBSITEDEV')."/view/images/icon_review.png"?>" rel="icon">
    <title>Reviews And Ratings</title>
    <script>
        var InertiaRequest = <?= Inertia::$request; ?>;
    </script>
</head>

<body>
    <context></context>
</body>

</html>