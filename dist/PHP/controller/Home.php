<?php
namespace Controller\HomeController;
use Pet\Controller\Controller;
use Pet\Inertia\Inertia;
use Pet\Request\Request;


class HomeController extends Controller{

    public function index(Request $request){
            Inertia::render('home', $request);
    }
}


?>