<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;


/**
 * CountryController implements the CRUD actions for Country model.
 */
class MainController extends Controller
{

    public function beginPage()
    {

        return $this->beginPage()->render('index');
    }
}

