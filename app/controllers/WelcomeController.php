<?php

namespace app\controllers;
use app\models\HomeModel;

use Flight;


class WelcomeController {

	public function __construct() {

	}

	public function home(){
		$model=new  HomeModel();
		Flight::render("home");
    }

	public function status(){
		$model=new  HomeModel();
		Flight::render("status_view",["status"=>$model->getData()]);
	}

 
}