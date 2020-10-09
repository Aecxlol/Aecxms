<?php


namespace Aecxms\Controller;


/**
 * Class HomeController
 * @package Aecxms\Controller
 */
class HomeController extends AbstractController
{
    public function index()
    {
        $test = "Salut à tous";

        $this->render('home/homepage.php', [
            'title' => 'Accueil',
            'test' => $test,
            'mdr' => 'on test juste on verra'
        ]);
    }
}