<?php


namespace Aecxms\Controller;


class HomeController extends AbstractController
{
    public function index() {
        $this->render('home/homepage.php');
    }
}