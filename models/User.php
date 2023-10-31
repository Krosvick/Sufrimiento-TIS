<?php

namespace Models;
use Core\Database;
use DAO\moviesDAO;
use GuzzleHttp;

class User{

    public $user_id;
    public $username;
    public $password;
    public $email;
    public $deleted_at;
    public $role;

    public function __construct($user_id = null, $username = null, $password = null, $email = null, $deleted_at = null, $role = "user"){
        $this->user_id=$user_id;
        $this->username=$username;
        $this->password=$password;
        $this->email=$email;
        $this->deleted_at=$deleted_at;
        $this->role=$role;
    }

    public function get_user_id(){
        return $this->user_id;
    }

    public function get_username(){
        return $this->username;
    }

    public function get_password(){
        return $this->password;
    }

    public  function get_email(){
        return $this->email;
    }

    public function get_deleted_at(){
        return $this->deleted_at;
    }

    public function get_role(){
        return $this->role;
    }

    public function get_recommended_movies($quantity): array{
        $client = new GuzzleHttp\Client();
        $movies = array();
        $response = $client->request('GET', 'localhost:8001/recommendations?user_id='.$this->user_id.'&quantity='.$quantity);
        $response = json_decode($response->getBody(), true);
        $movieDAO = new moviesDAO();
        #the response is a json that contains movies ids and a estimated rating for each movie
        #we need to get the movies from the database
        foreach($response as $movie){
            $movie = $movieDAO->find($movie['movie_id']);
            if ($movie != null){
                array_push($movies, $movie);
            }
        }
        return $movies;
    }

}