<?php
namespace src\handlers;

use \src\models\User;
use \src\models\UserRelation;
use \src\handlers\PostHandler;

class LoginHandler {

    public static function checkLogin() {

        if(!empty($_SESSION['token'])) {
            $token = $_SESSION['token'];

            $data = User::select()->where('token', $token)->one();
            if(count($data) > 0) {
    
                $loggedUser = new User();
                $loggedUser->id = $data['id'];
                $loggedUser->name = $data['name'];
                $loggedUser->email = $data['email'];
                $loggedUser->avatar = $data['avatar'];
                
                return $loggedUser;
            }
        }
    
        return false;
    }

    public static function verifyLogin($email, $password) {
        $user = User::select()->where('email', $email)->one();

        if($user) {

            if(password_verify($password, $user['password'])) {
                $token = md5(time().rand(0,999));

                User::update()
                    ->set('token', $token)
                    ->where('email', $email)
                ->execute();
                
                return $token;
            }
        }
        return false;
    }

    public static function idExists($id) {
        $user = User::select()->where('id', $id)->one();
        return $user ? true : false;
    }

    public static function emailExists($email) {
        $user = User::select()->where('email', $email)->one();
        return $user ? true : false;
    }

    public static function getUser($id, $full = false) {
        $data = User::select()->where('id', $id)->one();

        if($data) {
            $user = new User();
            
            $user->id = $data['id'];
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->birthdate = $data['birthdate'];
            $user->city = $data['city'];
            $user->work = $data['work'];
            $user->avatar = $data['avatar'];
            $user->cover = $data['cover'];

            if($full) {
                $user->followers = [];
                $user->following = [];
                $user->photo = [];

                //getting the user followers
                $followers = UserRelation::select()->where('user_to', $id)->get();
                foreach($followers as $follower) {
                    $userData = User::select()->where('id', $follower['user_from'])->one();

                    $newUser = new User();
                    $newUser->id = $userData['id'];
                    $newUser->name = $userData['name'];
                    $newUser->avatar = $userData['avatar'];

                    $user->followers[] = $newUser;
                }

                //getting who the user is following
                $following = UserRelation::select()->where('user_from', $id)->get();
                foreach($following as $follower) {
                    $userData = User::select()->where('id', $follower['user_to'])->one();
                    $newUser = new User();

                    $newUser->id = $userData['id'];
                    $newUser->name = $userData['name'];
                    $newUser->email = $userData['email'];
                    $newUser->avatar = $userData['avatar'];

                    $user->following[] = $newUser;
                }
                //getting the user photos
                $user->photo = PostHandler::getPhotoUser($id);
            }
            return $user;
        } 

        return false;

    }

    public static function addUser($name, $email, $password, $birthdate) {
        $token = md5(time().rand(0,999));
        $hash = password_hash($password, PASSWORD_DEFAULT);

        User::insert([
            'name' => $name,
            'email' => $email,
            'password' => $hash,
            'birthdate' => $birthdate,
            'token' => $token
        ])->execute();
        
        return $token;
    }

    public static function searchUser($search) {
        $users = [];

        $data = User::select()->where('name', 'like', '%'.$search.'%')->get();

        if($data) {
            foreach($data as $item) {
                $newUser = new User();
                $newUser->id = $item['id'];
                $newUser->name = $item['name'];
                $newUser->avatar = $item['avatar'];

                $users[] = $newUser;
            }
        }

        return $users;
    }

    public static function updateUser($fields, $idUser) {
        User::update()
            ->set('name', $fields['name'])
            ->set('birthdate', $fields['birthdate'])
            ->set('city', $fields['city'])
            ->set('work', $fields['work'])
        ->where('id', $idUser)->execute();

        if(!empty($fields['email'])) {
            User::update()
                ->set('email', $fields['email'])
                ->where('id', $idUser)
            ->execute();
        }

        if(!empty($fields['cover'])) {
            User::update()
                ->set('cover', $fields['cover'])
                ->where('id', $idUser)
            ->execute();
        }

        if(!empty($fields['avatar'])) {
            User::update()
                ->set('avatar', $fields['avatar'])
                ->where('id', $idUser)
            ->execute();
        }

        if(!empty($fields['password'])) {
            $hash = password_hash($fields['password'], PASSWORD_DEFAULT);
            User::update()
                ->set('password', $hash)
                ->where('id', $idUser)
            ->execute();
        }
    }
   

}  