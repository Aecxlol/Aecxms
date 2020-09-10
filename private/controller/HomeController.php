<?php


namespace Aecxms\Controller;

/**
 * Class HomeController
 * @package Aecxms\Controller
 */
class HomeController extends AbstractController
{
    public function index() {
        $this->render('home/homepage.php');
    }
}