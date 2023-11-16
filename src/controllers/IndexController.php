<?php

namespace Controllers;

use Core\BaseController;
use DAO\moviesDAO;
use Models\User;
use Models\Movie;
use GuzzleHttp\Client;

class IndexController extends BaseController
{
    public function index()
    {
        $test_user = new User();
        //dd($test_user);
        $test_user->user_id = 2;
        $test_movies = new Movie();
        $user_movies = $test_movies->find_movies($test_user->get_recommended_movies(6));

        $client = new Client();
        foreach($user_movies as $movie){
            /*

             this code should be in model or dao
            
             */
            $poster_url = 'https://image.tmdb.org/t/p/original'.$movie['poster_path'];
            try{
                $response = $client->request('GET', $poster_url);
            }catch(\Exception $e){
                if($e->getCode() == 404){
                    $new_poster_request = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$movie['id'].'/images?language=en', [
                        'headers' => [
                            'Authorization' => 'Bearer '. $_ENV['TMDB_API_KEY'],
                            'accept' => 'application/json',
                        ]
                    ]);
                    $new_poster_response = json_decode($new_poster_request->getBody(), true);
                    if(count($new_poster_response['posters']) > 0){
                        $new_poster_url = $new_poster_response['posters'][0]['file_path'];
                        $movie['poster_path'] = $new_poster_url;
                        #update the movie poster path in the database
                        $movieDAO = new moviesDAO();
                        $movieDAO->update($movie['id'], $movie, ['poster_path']);
                    }
                    else{
                        #if the movie doesn't have a poster, we will use a default image
                        $movie['poster_path'] = '';
                    }
                }
            }
        }
        $peliculas = new moviesDAO();
        $result = $peliculas->get_some(3, 0);
        $result2 = $peliculas->find(6);

        $data = [
            'title' => 'Home',
            'result' => $result,
            'result2' => $result2,
            'user_movies' => $user_movies
        ];
        return $this->render("partials/movie_page", $data);
    }
}