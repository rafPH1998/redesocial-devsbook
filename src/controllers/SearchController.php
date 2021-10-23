<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\LoginHandler;

class SearchController extends Controller {
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
        $search = filter_input(INPUT_GET, 's');

        if(empty($search)) {
            $this->redirect('/');
        }

        $users = LoginHandler::searchUser($search);

        $this->render('search', [
            'loggedUser' => $this->loggedUser,
            'search' => $search,
            'users' => $users
        ]);
    
    }

    

}