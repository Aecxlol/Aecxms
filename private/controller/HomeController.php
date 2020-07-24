<?php


namespace Aecxms\Controller;


use Aecxms\Model\AbstractModel;

class HomeController extends AbstractController
{
    public function index() {
        $this->render('index.php');
    }
}