<?php

namespace Models;
use Core\Database;
use Core\Model;
use DAO\moviesDAO;
use DAO\UsersDAO;
use GuzzleHttp;

/**
 * Class User
 *
 * The User class represents a user in the system. It extends the Model class and provides methods for user login, registration, and retrieving recommended movies and user movies. It also includes a static method to find a user by their ID.
 *
 * @package YourPackage
 */
class User extends Model{

    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $hashed_password;
    /**
     * @var string
     */
    private $first_name;
    /**
     * @var string
     */
    private $last_name;
    /**
     * @var string
     */ 
     private $created_at;
    /**
     * @var string
     */
    private $updated_at;
    /**
     * @var string
     */
    private $deleted_at;
    /**
     * @var string
     */
    private $role = 'user';

    public function __construct($id = null, $username = null, $hashed_password = null, $first_name = null, $last_name = null, $created_at = null, $updated_at = null, $deleted_at = null, $role = null){
        $this->id = $id;
        $this->username = $username;
        $this->hashed_password = $hashed_password;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->role = $role ?? $this->role;
        $this->DAOs = [
            'tableDAO' => new UsersDAO()
        ];
    }

    /**
     * Get the user ID.
     *
     * @return int The user ID.
     */
    public function get_id(){
        return $this->id;
    }

    /**
     * Set the user ID.
     *
     * @param int $id The user ID.
     */
    public function set_id($id){
        $this->id = $id;
    }

    /**
     * Get the username.
     *
     * @return string The username.
     */
    public function get_username(){
        return $this->username;
    }

    /**
     * Set the username.
     *
     * @param string $username The username.
     */
    public function set_username($username){
        $this->username = $username;
    }

    /**
     * Get the hashed password.
     *
     * @return string The hashed password.
     */
    public function get_hashed_password(){
        return $this->hashed_password;
    }

    /**
     * Set the hashed password.
     *
     * @param string $hashed_password The hashed password.
     */
    public function set_hashed_password($hashed_password){
        $this->hashed_password = $hashed_password;
    }

    /**
     * Get the first name.
     *
     * @return string The first name.
     */
    public function get_first_name(){
        return $this->first_name;
    }

    /**
     * Set the first name.
     *
     * @param string $first_name The first name.
     */
    public function set_first_name($first_name){
        $this->first_name = $first_name;
    }

    /**
     * Get the last name.
     *
     * @return string The last name.
     */
    public function get_last_name(){
        return $this->last_name;
    }

    /**
     * Set the last name.
     *
     * @param string $last_name The last name.
     */
    public function set_last_name($last_name){
        $this->last_name = $last_name;
    }

    /**
     * Get the created at timestamp.
     *
     * @return string The created at timestamp.
     */
    public function get_created_at(){
        return $this->created_at;
    }

    /**
     * Set the created at timestamp.
     *
     * @param string $created_at The created at timestamp.
     */
    public function set_created_at($created_at){
        $this->created_at = $created_at;
    }

    /**
     * Get the updated at timestamp.
     *
     * @return string The updated at timestamp.
     */
    public function get_updated_at(){
        return $this->updated_at;
    }

    /**
     * Set the updated at timestamp.
     *
     * @param string $updated_at The updated at timestamp.
     */
    public function set_updated_at($updated_at){
        $this->updated_at = $updated_at;
    }

    /**
     * Get the deleted at timestamp.
     *
     * @return string The deleted at timestamp.
     */
    public function get_deleted_at(){
        return $this->deleted_at;
    }

    /**
     * Set the deleted at timestamp.
     *
     * @param string $deleted_at The deleted at timestamp.
     */
    public function set_deleted_at($deleted_at){
        $this->deleted_at = $deleted_at;
    }

    /**
     * Get the user role.
     *
     * @return string The user role.
     */
    public function get_role(){
        return $this->role;
    }

    /**
     * Set the user role.
     *
     * @param string $role The user role.
     */
    public function set_role($role){
        $this->role = $role;
    }

    /**
     * Get the primary key for the User class.
     *
     * @return string The primary key.
     */
    public static function primaryKey(){
        return 'id';
    }

    /**
     * Get the attributes of the User class.
     *
     * @return array The attributes.
     */
    public function attributes(){
        return [
            'username',
            'hashed_password',
            'first_name',
            'last_name',
            'created_at',
            'updated_at',
            'deleted_at',
            'role'
        ];
    }
    
    
    /**
     * Defines the validation rules for user attributes.
     *
     * @return array The validation rules.
     */
    public function rules()
    {
        return [
            'username' => [self::RULE_UNIQUE],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 255]],
            'hashed_password' => [self::RULE_PASSWORD_MATCH, 'password_match' => 'confirm_password'],
        ];
    }

    /**
     * Logs in a user.
     *
     * @param DAO $DAO The DAO instance used for database operations.
     * @param string $username The username of the user.
     * @param string $password The password of the user.
     * @return mixed The user data if the login is successful, or an error message if the login fails.
     */
    public function login($DAO, $username, $password)
    {
        $user_data = $DAO->findBy($username, 'username');
        if ($user_data) {
            if (!password_verify($password, $user_data->hashed_password)) {
                $this->addError('password', 'User name or password are not valid');
            }
        } else {
            $this->addError('username', 'User name or password are not valid');
        }
        return $user_data;
    }

    /**
     * Retrieves a specified number of recommended movie IDs for the user.
     *
     * @param int $quantity The number of movie IDs to retrieve.
     * @return array The recommended movie IDs.
     */
    public function getRecommendedMoviesIds($quantity): array
    {
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', 'localhost:8001/recommendations/ids?userId=' . $this->id . '&n=' . $quantity);
        $response = json_decode($response->getBody(), true);
        return $response;
    }

    /**
     * Retrieves a specified number of movies for the user.
     *
     * @param int $quantity The number of movies to retrieve.
     * @return array The user movies.
     */
    public function get_user_movies($quantity): array
    {
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', 'localhost:8001/user-movies?userId=' . $this->id . '&n=' . $quantity);
        $response = json_decode($response->getBody(), true);
        return $response;
    }

    public function get_liked_movies($RatingsDAO, $MoviesDAO, $quantity): array
    {
        $ratings = $RatingsDAO->get_liked_movies($this->id, $quantity);
        $movies = [];
        foreach($ratings as $rating) {
            $movie = $MoviesDAO->find($rating->movie_id, Movie::class);
            $movie->movieposterfallback();
            array_push($movies, $movie);
        }

        //$movies = $MoviesDAO->get_liked_movies($this->id, $quantity);
        return $movies;
    }

    /**
     * Finds a user by their ID.
     *
     * @param int $id The ID of the user.
     * @return mixed The user object found by ID.
     */
    public static function findOne($id)
    {
        $userDAO = new UsersDAO();
        $user = $userDAO->find($id, static::class);
        return $user;
    }

    public function has_rated_movie($movie_id, $RatingsDAO): bool{
        $rating = $RatingsDAO->find_by_user_and_movie($this->id, $movie_id);
        if($rating){
            return true;
        } else {
            return false;
        }
    }
}