<?php

declare(strict_types=1);

namespace App\Valinor;

use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\Source\Source;
use CuyZ\Valinor\MapperBuilder;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function count;

final readonly class ValinorResolver
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * @param class-string<T> $class
     *
     * @return T
     *
     * @template T of object
     */
    public function resolveDto(Request $request, string $class)
    {
        $dto = (new MapperBuilder())
            ->mapper()
            ->map($class, Source::json($request->getContent()));

        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            throw new ViolationException($violations);
        }

        return $dto;
    }
}
