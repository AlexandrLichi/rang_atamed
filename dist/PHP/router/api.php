<?php

use Controller\ApiController\ApiController;
use Model\ClientModel\ClientModel;
use Pet\Router\Router;

Router::get('/api/{parser}', [ApiController::class, 'updateReviews']);
Router::get('/feedback/{api}/{id}', [ApiController::class, 'feedback']);
Router::put('/feedback/{api}/{id}', [ApiController::class, 'sendClient']);
Router::get('/match', [ApiController::class, 'match']);
Router::get('/paralele', [ApiController::class, 'paralele']);
Router::get('/imap-update', [ApiController::class, 'updateImap']);
Router::get('/callback-parser', [ApiController::class, 'updateReviewsCallback']);

//внешние подключение js widget 
Router::get('/widget/js', [ApiController::class, 'getJsWigets']);
Router::get('/widget/css', [ApiController::class, 'getCssWigets']);
Router::get('/widget/{api}', [ApiController::class, 'getDataWidgets']);
Router::options('/widget/{api}', [ApiController::class, 'getDataWidgets']);
?>