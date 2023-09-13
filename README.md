
### Simple Example for an API build with symfony and valinor

### Run the app

#### Install dependencies
```bash
composer install
```

#### Start the app (symfony cli needed)
```bash
symfony server:start
```

#### Make a Request
Make a http `POST` request with a client of your choice on `http://localhost:8000/books`

Payload:
```json
{
  "title": "Advaned Web Application Infrastructure",
  "description": "The missing manual for making your web applications future-proof", 
  "author": "Matthias Noback",
  "isbn": 9789082120165,
  "links": [
    {
      "href": "https://matthiasnoback.nl/book/advanced-web-application-architecture/",
      "title": "matthiasnoback.nl"
    }
  ]
}

```

### How it works
First we have a simple `App\Controller\CreateBookController` to create a book via `POST` on `/books`

The request data is validated and serialized to the CreateBookDto via the `App\Valinor\ValinorResolver`.
The resolver validates the structure first, then serializes the data into the specified DTO class and finally
validates the content via the symfony validator.

Any structural or content related errors are collected and then thrown as MappingError or ViolationException.
These Exceptions are translated to corresponding HTTP error responses in the `App\Valinor\ValinorExceptionListener`.

The whole example comes with functional tests using snapshot files, which give good impressions over the actual 
responses this approach produces. snapshot files stored at `tests/Functional/__snapshots__/`.

### Tests

Tests can be executed via 
```bash
bin/phpunit
```


