<?php

use Controller\AdminController\AdminController;
use Controller\ClientController\ClientController;
use Controller\HomeController\HomeController;
use Controller\LoginController\LoginController;
use Controller\TechController\TechController;
use Pet\Router\Router;
use Middleware\AuthMiddleware\AuthMiddleware;
use Middleware\TechAuthMiddleware\TechAuthMiddleware;
use Model\FilialModel\FilialModel;
use Model\ParsersModel\ParsersModel;

Router::middleware([AuthMiddleware::class, 'Auth'])->group("Auth")->setRout(

     Router::get('/admin',[AdminController::class, 'index']),
     Router::get('/admin/reviews', [AdminController::class, 'reviews']),
     
     Router::get('/admin/request', [AdminController::class, 'request']),
     Router::post('/admin/request/set-bunus', [AdminController::class, 'setBonus']),

     Router::get('/admin/setting', [AdminController::class, 'settingPage']),
     Router::put('/admin/setting/new-users',[AdminController::class, 'newUser']),
     Router::put('/admin/setting/update-password', [AdminController::class, 'updatePass']),

     Router::post('/admin/settig/sample-image', [AdminController::class, 'setFileSample']),
     Router::post('/admin/settig/sample-text', [AdminController::class, 'setTextSample']),
     Router::post('/admin/settig/sample-test', [AdminController::class, 'setTestSample']),
     Router::post('/admin/settig/sample-bonus', [AdminController::class, 'setBonusSample']),
     Router::put("/admin/setting/set-imap", [AdminController::class, 'setImap']),
     Router::post('/admin/setting-mess-file', [AdminController::class, 'setUrlImageWA']),
   
     Router::get("/admin/myfilial", [AdminController::class, 'myfilial']),
     Router::put("/admin/myfilial/toggle", [FilialModel::class, 'toggleActiv']),

     Router::get("/admin/analytics", [AdminController::class, 'analytics']),
     Router::post("/admin/analytics", [AdminController::class, 'analyticsSearch']),

     Router::get("/admin/help", [AdminController::class, 'help']),

);

Router::middleware([TechAuthMiddleware::class, 'TechAuth'])->group("TechAuth")->setRout(

     Router::get('/tech', [TechController::class, 'index']),
     
     Router::get('/tech/new-project', [TechController::class, 'NewProject']),
     Router::post('/tech/new-project', [TechController::class, 'getNewApi']),
     Router::put('/tech/new-project', [TechController::class, 'createCompany']),
     Router::post('/tech/new-parser', [TechController::class, 'newParser']),
     
     Router::get('/tech/plat/{parser}',[TechController::class, 'parser']),
     Router::put('/tech/plat/{parser}', [ParsersModel::class, 'updateSetting']),
     Router::post('/tech/plat/{parser}', [TechController::class, 'putNewFilial']),
     Router::post('/tech/url_default/{id}', [TechController::class, 'replaceUrlDefault']),
     Router::post('/tech/new_param/{id}', [TechController::class, 'newParam']),
     
     Router::post('/tech/filial/{api}', [TechController::class, 'listfilial']),

     Router::get("/tech/companies", [TechController::class, 'companies'] ),
     Router::get("/tech/download/{api}", [TechController::class, 'downloand']),
     Router::post("/tech/download/{api}/{id}", [TechController::class, 'downloandParser']),
     Router::get("/tech/setting", [TechController::class, 'settingConfig']),
     Router::put("/tech/setting", [TechController::class, 'setConfig']),

     Router::get("/tech/upload", [TechController::class, 'uploadIndex']),
     Router::get("/tech/logs", [TechController::class, 'logs']),
     Router::get("/tech/client", [TechController::class, 'TechClient']),
     Router::put("/tech/logs", [TechController::class, 'clearlogs']),
     Router::get("/tech/news", [TechController::class, 'News']),
     Router::post("/tech/news", [TechController::class, 'setNews']),


);



Router::get("/login", [LoginController::class, 'index']);
Router::post("/login", [LoginController::class, 'check']);

Router::get('/otzyv/{url}', [ClientController::class, 'index']);
Router::post('/otzyv/{url}', [ClientController::class, 'putClientParam']);
// Router::get('/otzyv/{url}', [ClientController::class, 'index']);
// Router::put('/otzyv/{url}', [ClientController::class, 'set']);



Router::get("/", [HomeController::class, 'index']);

include_once(__DIR__.'/api.php');

?>