<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Spatie\Snapshots\MatchesSnapshots;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function dd;
use function json_decode;
use function json_encode;

final class CreateBookTest extends WebTestCase
{
    use MatchesSnapshots;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    public function testCreatesBookSuccessfully(): void
    {
        $payload = [
            'author' => 'Max Mustermann',
            'title' => 'Foo',
            'description' => 'Lorem ipsum',
            'isbn' => 1111111111111,
            'links' => [
                [
                    "href" => "http://google.com",
                    "title" => "google.com",
                ],
            ],
        ];
        $this->client->request(
            'POST',
            '/books',
            content: json_encode($payload),
        );

        $this->assertResponseStatusCodeSame(201);
    }

    public function testTypeValidationReturnsError(): void
    {
        $payload = [];
        $this->client->request(
            'POST',
            '/books',
            content: json_encode($payload),
        );

        $this->assertMatchesJsonSnapshot($this->client->getResponse()->getContent());
        $this->assertResponseStatusCodeSame(400);
    }

    /** @dataProvider provideBuisinessValidationErrors */
    public function testBusinessValidationReturnsError($payload): void
    {
        $this->client->request(
            'POST',
            '/books',
            content: json_encode($payload),
        );

        $this->assertMatchesJsonSnapshot($this->client->getResponse()->getContent());
        $this->assertResponseStatusCodeSame(400);
    }

    public function provideBuisinessValidationErrors(): iterable
    {
        yield 'isbn is too short' => [
            [
                'author' => 'Max Mustermann',
                'title' => 'Foo',
                'description' => 'Lorem ipsum',
                'isbn' => 1,
                'links' => [
                    [
                        "href" => "http://google.com",
                        "title" => "google.com",
                    ],
                ],
            ],
        ];
        yield 'Title must not be empty' => [
            [
                'author' => 'Max Mustermann',
                'title' => '',
                'description' => 'Lorem ipsum',
                'isbn' => 1111111111111,
                'links' => [
                    [
                        "href" => "http://google.com",
                        "title" => "google.com",
                    ],
                ],
            ],
        ];
        yield 'Multiple validation errors' => [
            [
                'author' => '',
                'title' => '',
                'description' => 'Lorem ipsum',
                'isbn' => 1,
                'links' => [
                    [
                        "href" => "http://google.com",
                        "title" => "google.com",
                    ],
                ],
            ],
        ];
        yield 'Children error' => [
            [
                'author' => 'Max Mustermann',
                'title' => 'Foo',
                'description' => 'Lorem ipsum',
                'isbn' => 1111111111111,
                'links' => [
                    [
                        "href" => "",
                        "title" => "",
                    ],
                ],
            ],
        ];
    }
}
