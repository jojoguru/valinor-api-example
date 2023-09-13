<?php

declare(strict_types=1);

namespace App\Valinor;

use CuyZ\Valinor\Mapper\MappingError;
use CuyZ\Valinor\Mapper\Tree\Message\Messages;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

use Symfony\Component\Validator\ConstraintViolation;

use function array_key_exists;
use function explode;

final class ValinorExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof MappingError) {
            $messages = Messages::flattenFromNode(
                $exception->node(),
            );

            $errors = [];
            foreach ($messages as $message) {
                $parts = explode('.', $message->node()->path(), 2);
                $path = $parts[1] ?? $parts[0];

                if (!array_key_exists($path, $errors)) {
                    $errors[$path]['errors'] = [];
                }

                $errors[$path]['errors'][] = $message->toString();
            }

            $response = new JsonResponse([
                'code' => 400,
                'message' => 'Validation Failed',
                'errors' => ['children' => $errors],
            ], 400);

            $event->setResponse($response);
        }

        if ($exception instanceof ViolationException) {
            $errors = [];

            foreach ($exception->getConstraintViolationList() as $violation) {
                assert($violation instanceof ConstraintViolation);
                $parts = explode('.', $violation->getPropertyPath(), 2);
                $path = $parts[1] ?? $parts[0];

                if (!array_key_exists($path, $errors)) {
                    $errors[$path]['errors'] = [];
                }

                $errors[$path]['errors'][] = $violation->getMessage();
            }

            $response = new JsonResponse([
                'code' => 400,
                'message' => 'Validation Failed',
                'errors' => ['children' => $errors],
            ], 400);

            $event->setResponse($response);
        }
    }
}
