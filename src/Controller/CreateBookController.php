<?php

declare(strict_types=1);

namespace App\Controller;

use App\RequestModel\CreateBookDto;
use App\Valinor\ValinorResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
final class CreateBookController
{
    public function __construct(private readonly ValinorResolver $resolver) {
    }

    #[Route('/books', name: 'book_create', methods: 'POST')]
    public function __invoke(Request $request): Response
    {
        $bookDto = $this->resolver->resolveDto($request, CreateBookDto::class);

        // use $bookDto to save to repository, dispatch domain event or do whatever you want here

        return new Response(status: 201);
    }
}
