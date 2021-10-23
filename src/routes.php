<?php
use core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');

//login-related routes
$router->get('/login', 'LoginController@signin');
$router->post('/login', 'LoginController@signinAction');

//routes related to registration
$router->get('/cadastro', 'LoginController@signup');
$router->get('/sair', 'LoginController@logout');
$router->post('/cadastro', 'LoginController@signupAction');

//profile-related routes
$router->get('/perfil/{id}/fotos', 'ProfileController@photos');
$router->get('/perfil/{id}/amigos', 'ProfileController@friends');
$router->get('/perfil/{id}/follow', 'ProfileController@follow');
$router->get('/perfil/{id}', 'ProfileController@profile');
$router->get('/perfil', 'ProfileController@profile');
$router->get('/amigos', 'ProfileController@friends'); 
$router->get('/fotos', 'ProfileController@photos');

//route related to user profile search
$router->get('/pesquisa', 'SearchController@index');

//route related to user profile configuration
$router->get('/config', 'ConfigController@config');
$router->post('/config', 'ConfigController@save');

//routes related to like, comments and sending photos
$router->get('/ajax/like/{id}', 'AjaxController@like');
$router->post('/ajax/comment', 'AjaxController@comment');
$router->post('/ajax/upload', 'AjaxController@upload');

//routes about posting a post and deleting a post
$router->post('/new', 'PostController@new');
$router->get('/post/{id}/delete', 'PostController@delete');


