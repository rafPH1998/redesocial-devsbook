<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\LoginHandler;

class LoginController extends Controller {

    public function signin() {
        $flash = '';
        if(!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }

        $this->render('login', [
            'flash' => $flash
        ]);
    }

    public function signinAction() {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');

        if($email && $password) {
            $token = LoginHandler::verifyLogin($email, $password);

            if($token) {
                $_SESSION['token'] = $token; 

                $this->redirect('/');

            } else {
                $_SESSION['flash'] = 'Email e/ou senha incorretos!';
                $this->redirect('/login');
            }
        } else {
            $_SESSION['flash'] = 'Preencha todos os campos!';
            $this->redirect('/login');
        }
    }

    public function signup() {
        $flash = '';
        if(!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }

        $this->render('cadastro', [
            'flash' => $flash
        ]);
    }

    public function signupAction() {
        $name = filter_input(INPUT_POST, 'name');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');
        $birthdate = filter_input(INPUT_POST, 'birthdate');

        if($name && $email && $password && $birthdate) {
            
            //verifying if the user sent all the dates: date, month and year of birth
            $birthdate = explode('/', $birthdate);
            if(count($birthdate) != 3) {
                $_SESSION['flash'] = 'Data de Náscimento inválida!';
                $this->redirect('/cadastro');
            }

            // checking the date of birth if it is correct, if the numbers are real
            $birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];

            if(strtotime($birthdate) === false) {
                $_SESSION['flash'] = 'Data de Náscimento inválida!';
                $this->redirect('/cadastro');
            }

            //checking if the year is less than or equal to the current year
            $birthdate = explode('/', $birthdate);
            if($birthdate[0] > date('Y')) {
                $_SESSION['flash'] = 'Ano de Náscimento inválido!';
                $this->redirect('/cadastro');
            }

            //verifying if the email that the user is registering already exists
            if(LoginHandler::emailExists($email) === false) {
                $token = LoginHandler::addUser($name, $email, $password, $birthdate);
                $_SESSION['token'] = $token;
                $this->redirect('/');
                
            } else {
                $_SESSION['flash'] = 'Esse email já esta cadastrado!';
                $this->redirect('/cadastro');
            }

        } else {
            $_SESSION['flash'] = 'Preencha todos os campos para fazer o cadastro!';
            $this->redirect('/cadastro');

        }   
    }

    public function logout() {
        $_SESSION['token'] = '';
        $this->redirect('/login');
    }
    

}