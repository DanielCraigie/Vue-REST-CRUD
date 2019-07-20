<?php

namespace App\controllers\web;

use App\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Testing
 * @package App\controllers\web
 */
class Testing
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $twig = App::utils()->getTwigLoader();
        return $twig->render('testing/index.html.twig');
    }
}
