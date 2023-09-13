<?php

declare(strict_types=1);

namespace App\RequestModel;

use Symfony\Component\Validator\Constraints\NotBlank;

final class LinkDto
{
    #[NotBlank()]
    public string $href;

    #[NotBlank()]
    public string $title;

    /** @return array{href: string, title: string} */
    public function asArray(): array
    {
        return [
            'href' => $this->href,
            'title' => $this->title,
        ];
    }
}
