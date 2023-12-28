<?php

namespace Controllers;

use DAO\UsersDAO;
use Core\BaseController;
use DAO\moviesDAO;
use DAO\RatingsDAO;
use Models\User;
use Models\Movie;
use Core\Application;
use GuzzleHttp\Client;
use Core\Middleware\AuthMiddleware;

/**
 * UserController class
 *
 * This class is responsible for handling user-related functionality in the application.
 */
class UserController extends BaseController{
    private $userDAO;
    public $user;

    /**
     * UserController constructor
     *
     * Initializes the UserController class by calling the parent constructor and setting the user and userDAO properties.
     *
     * @param object $container An object representing the application's container.
     * @param array $routeParams An array containing the route parameters.
     * @return void
     */
    public function __construct($container, $routeParams) {
        parent::__construct(...func_get_args());
        $this->user = Application::$app->session->get('user') ?? null;
        $this->userDAO = new UsersDAO();
    }

    /**
     * LikedMovies method
     *
     * Renders a page that displays the movies liked by a user.
     *
     * @return string The rendered HTML content of the "likedpost" template.
     */
    public function likedMovies($id){
        $ratingsDAO = new RatingsDAO();
        $MoviesDAO = new MoviesDAO();

        $isLogged = !Application::isGuest();
        $loggedUserId = $isLogged ? $this->user->get_id() : null;

        if($isLogged && $loggedUserId == $id){
            $username = $this->user->get_username();
            $user_movies = $this->user->get_liked_movies($ratingsDAO, $MoviesDAO, 10);
            $userData = $this->user;
        }else{
            $userData = $this->userDAO->find($id, User::class);
            $user_movies = $userData->get_liked_movies($ratingsDAO, $MoviesDAO, 10);
            $username = $userData->get_username();
        }
        $data = [
            'user_movies' => $user_movies,
            'username' => $username,
            'userData' => $userData,
        ];
        $metadata = [
            'title' => 'Pirateca - Profile',
            'description' => 'This is the profile page of the user.',
            'cssFiles' => [
                '' // TODO: add css files here
            ],
        ];
        $optionals = [
            'data' => $data,
            'metadata' => $metadata
        ];

        return $this->render("likedpost", $optionals);
    }

    public function profilePage($id){

        $userProfileData = $this->userDAO->find($id, User::class);
        

        $data = [
            'loggedUser' => $this->user,
            'userProfileData' => $userProfileData,
        ];
        $metadata = [
            'title' => 'Pirateca - Profile',
            'description' => 'This is the profile page of the user.',
            'cssFiles' =>  ['styles_nav.css'],
        ];
        $optionals = [
            'data' => $data,
            'metadata' => $metadata
        ];

        return $this->render('profile', $optionals);
    }

    /**
     * Logout method
     *
     * Logs out the user by clearing the user session and redirecting the user to the homepage.
     *
     * @return void
     */
    public function logout(){
        if($this->user){
            Application::$app->logout();
            header('Location: /');
        }
        else{
            header('Location: /');
        }
    }
}
