<?php

namespace Core;

class Response
{
    protected $view;

    public function __construct($base_url = NULL)
    {
        $this->view = new View($base_url);
    }
    public function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    public function abort($code = 404)
    {
        http_response_code($code);
        $this->view->render($code);
        die();
    }

    public function render($view, $data = [])
    {
        $this->view->render($view, $data);
    }

}
