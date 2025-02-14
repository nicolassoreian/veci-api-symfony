<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CustomerInfo extends AbstractController
{
    public function __construct(
    ) {
    }

    public function __invoke()
    {
        return 'a';
    }
}
