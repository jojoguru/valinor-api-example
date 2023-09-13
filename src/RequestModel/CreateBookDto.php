<?php

declare(strict_types=1);

namespace App\RequestModel;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

final class CreateBookDto
{
    #[NotBlank()]
    public string $author;

    #[NotBlank()]
    public string $title;

    #[NotBlank()]
    public string $description;

    #[NotBlank()]
    #[Length(13)]
    public int $isbn;

    #[Valid()]
    /** @var array<LinkDto> */
    public array $links = [];
}
