<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\LoginHandler;
use \src\handlers\PostHandler;

class ProfileController extends Controller {
    private $loggedUser;

    public function __construct() {
        $this->loggedUser = LoginHandler::checkLogin();

        if($this->loggedUser === false ) {
            $this->redirect('/login');
        } else {
            false;
        }

    }

    public function profile($atts = []) {
        $page = intval(filter_input(INPUT_GET, 'page'));

        //detecting the accessed user
        $id = $this->loggedUser->id;
        if(!empty($atts['id'])) {
            $id = $atts['id'];
        }

        //getting the user information
        $user = LoginHandler::getUser($id, true);
        if(!$user) {
            $this->redirect('/');
        } 

        $dateFrom = new \DateTime($user->birthdate);
        $dateTo = new \DateTime('today');
        $user->ageYear = $dateFrom->diff($dateTo)->y;

        //gettind the user feed
        $feed = PostHandler::getUserFeed(
            $id,
            $page,
            $this->loggedUser->id    
        );
        
        //checking if the user is following the other user
        $isFollowing = false;
        if($user->id != $this->loggedUser->id) {
            $isFollowing = PostHandler::isFollowing($this->loggedUser->id, $user->id);
        }

        $this->render('profile', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'feed' => $feed,
            'isFollowing' => $isFollowing
        ]);
    }

    public function follow($atts) {
        $to = intval($atts['id']);

        //checking if this user exists
        if(LoginHandler::idExists($to)) {
            //checking if the user is follower the user
            if(PostHandler::isFollowing($this->loggedUser->id, $to)) {
                //onFollow
                PostHandler::unFollow($this->loggedUser->id, $to);
            } else {
                //follow
                PostHandler::follow($this->loggedUser->id, $to);
            }
        }

        $this->redirect('/perfil/'.$to);
    }

    public function friends($atts = []) {
         //detecting the accessed user
         $id = $this->loggedUser->id;
         if(!empty($atts['id'])) {
             $id = $atts['id'];
         }
 
         //getting the user information
         $user = LoginHandler::getUser($id, true);
         if(!$user) {
             $this->redirect('/');
         } 

        $isFollowing = false;
        if($user->id != $this->loggedUser->id) {
            $isFollowing = PostHandler::isFollowing($this->loggedUser->id, $user->id);
        }

        $this->render('profile_friends', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'isFollowing' => $isFollowing
        ]);

    }

    public function photos($atts = []) {
        //detecting the accessed user
        $id = $this->loggedUser->id;
        if(!empty($atts['id'])) {
            $id = $atts['id'];
        }

        //getting the user information
        $user = LoginHandler::getUser($id, true);
        if(!$user) {
            $this->redirect('/');
        } 

        $isFollowing = false;
        if($user->id != $this->loggedUser->id) {
            $isFollowing = PostHandler::isFollowing($this->loggedUser->id, $user->id);
        }

        $this->render('profile_photos', [
            'loggedUser' => $this->loggedUser,
            'user' => $user,
            'isFollowing' => $isFollowing
        ]);

    }



    

}