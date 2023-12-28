<?php

namespace DAO;

use Core\Database;
use Core\DAO;
use Models\Movie;
use Exception;
use PDO;

class RatingsDAO extends DAO {

    public function __construct() {
        $this->table = 'ratings';
        parent::__construct();
    }

    /**
     * Retrieves all the data from the `ratings` table.
     *
     * @return array An array of rows containing all the data from the `ratings` table.
     */
    public function get_all_data() {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $stmt = $this->connection->query($sql);
            $rows = $stmt->get();
            return $rows;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Retrieves ratings data from the database based on a given movie object.
     *
     * @param Movie $movie A Movie object representing the movie for which ratings are to be retrieved.
     * @return array An array of rows containing the ratings data for the given movie.
     */
    public function getByMovie(Movie $movie): array {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE movie_id = :movie_id LIMIT :limit";
            $params = array(
                'movie_id' => [$movie->get_id(), PDO::PARAM_INT],
                'limit' => [10, PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            $rows = $stmt->get();
            return $rows;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

     /**
     * Retrieves ratings data from the database based on a given movie object.
     *
     * @param Movie $movie A Movie object representing the movie for which ratings are to be retrieved.
     * @return array An array of rows containing the ratings data for the given movie.
     */
    public function getPagebyMovie(Movie $movie, $lastId): array {
        try {
            $rowsPerPage = 10;
            $lastId = (int) $lastId;

            // Query to get the rows for the current page
            $sql = "SELECT * FROM {$this->table} FORCE INDEX (movie_id_id_index) WHERE movie_id = :movie_id AND id > :last_id ORDER BY id LIMIT :limit";
            $params = array(
                'movie_id' => [$movie->get_id(), PDO::PARAM_INT],
                'last_id' => [$lastId, PDO::PARAM_INT],
                'limit' => [$rowsPerPage, PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            $rows = $stmt->get();

            if (empty($rows)) {
                return [
                    'message' => 'No ratings found for this movie.'
                ];
            }// If there are no rows, return an appropriate response
            
            //from rows remove all the rows where user_id is above 906
            $rows = array_filter($rows, function($row) {
                return $row->user_id <= 908;
            });
            if (empty($rows)) {
                return [
                    'message' => 'No ratings found for this movie.'
                ];
            }
            
            $firstId = $rows[0]->id;
            $lastId = end($rows)->id;

            $lastResultSql = "SELECT id FROM (SELECT id FROM {$this->table} WHERE movie_id = :movie_id ORDER BY id DESC LIMIT 10) sub ORDER BY id ASC LIMIT 1";
            $firstIdParams = array(
                'movie_id' => [$movie->get_id(), PDO::PARAM_INT]
            );
            $lastResultStmt = $this->connection->query($lastResultSql, $firstIdParams);
            $lastResult = $lastResultStmt->statement->fetchColumn();

            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE movie_id = :movie_id";
            $params = array(
                'movie_id' => [$movie->get_id(), PDO::PARAM_INT]
            );
            $stmt = $this->connection->query($sql, $params);
            $totalRows = $stmt->statement->fetchColumn();

            return [
                'rows' => $rows,
                'firstId' => $firstId,
                'lastId' => $lastId,
                'lastResults' => $lastResult,
                'totalRows' => $totalRows
            ];
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function get_liked_movies($user_id, $quantity) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY user_id LIMIT :quantity";
            $params = array(
                'user_id' => [$user_id, PDO::PARAM_INT],
                'quantity' => [$quantity, PDO::PARAM_INT],
            );
            $stmt = $this->connection->query($sql, $params);
            $rows = $stmt->get();
            return $rows;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function find_by_user_and_movie($user_id, $movie_id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id AND movie_id = :movie_id";
            $params = array(
                'user_id' => [$user_id, PDO::PARAM_INT],
                'movie_id' => [$movie_id, PDO::PARAM_INT],
            );
            $stmt = $this->connection->query($sql, $params);
            $row = $stmt->get();
            return $row;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}