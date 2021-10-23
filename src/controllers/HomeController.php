<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\LoginHandler;
use \src\handlers\PostHandler;

class HomeController extends Controller {
    private $loggedUser;

    public function __construct() {
        $this->loggedUser = LoginHandler::checkLogin();

        if($this->loggedUser === false ) {
            $this->redirect('/login');
        } else {
            false;
        }

    }

    public function index() {
        $page = intval(filter_input(INPUT_GET, 'page'));

        //getting the user informations
        $feed = PostHandler::getHomeFeed(
            $this->loggedUser->id,
            $page
        );

        $this->render('home', [
            'loggedUser' => $this->loggedUser,
            'feed' => $feed
        ]);
    }

    

}