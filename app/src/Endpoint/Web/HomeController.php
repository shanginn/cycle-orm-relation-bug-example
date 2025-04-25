<?php

declare(strict_types=1);

namespace App\Endpoint\Web;

use Exception;
use Spiral\Router\Annotation\Route;
use Spiral\Views\ViewsInterface;

/**
 * Simple home page controller. It renders home page template and also provides
 * an example of exception page.
 */
final class HomeController
{
    public function __construct(private readonly ViewsInterface $views) {}

    #[Route(route: '/', name: 'index')]
    public function index(): string
    {
        return $this->views->render('home');
    }

    #[Route(route: '/(oferta|%D0%BE%D1%84%D0%B5%D1%80%D1%82%D0%B0)', name: 'oferta')]
    public function oferta(): string
    {
        return $this->views->render('oferta');
    }

    #[Route(route: '/(tariffs|%D1%82%D0%B0%D1%80%D0%B8%D1%84%D1%8B)', name: 'tariffs')]
    public function tariffs(): string
    {
        return $this->views->render('tariffs');
    }

    /**
     * Example of exception page.
     */
    #[Route(route: '/exception', name: 'exception')]
    public function exception(): never
    {
        throw new Exception('This is a test exception.');
    }
}
