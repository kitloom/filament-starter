<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Livewire\Livewire;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // The theme renders the page via Vite; tests don't need built assets.
        $this->withoutVite();

        $this->startAsTheNextRequest();

        // Livewire keeps "a component was rendered in this request" in a
        // static, reset only when a request ends. A Livewire::test() earlier
        // in the same process leaves it set, and the next page of the site
        // would get Livewire's assets injected before </head>.
        Livewire::flushState();
    }

    /**
     * The test starts as the next request on the refreshed database would.
     * Where the tables are there at boot (MySQL; SQLite in memory gets them
     * only from RefreshDatabase), the boot has already read what lives for
     * one request — the options, the site timezone — before the test wrote
     * its rows, and the array cache keeps the options as long as the
     * application.
     */
    protected function startAsTheNextRequest(): void
    {
        $this->app->forgetScopedInstances();

        if ($this->app->make('config')->get('cache.default') === 'array') {
            $this->app->make('cache')->store()->flush();
        }
    }

    /**
     * Site addresses end with a slash, as in WordPress, and the site sends
     * any other form there with a 301. Laravel's test client cuts the slash
     * off: `get('/about/')` would ask for `/about`. It is put back.
     *
     * @param  string  $uri
     */
    protected function prepareUrlForRequest($uri)
    {
        $url = parent::prepareUrlForRequest($uri);
        $path = (string) parse_url((string) $uri, PHP_URL_PATH);

        return $path !== '/' && str_ends_with($path, '/') && ! str_contains($url, '?') && ! str_ends_with($url, '/') ? $url.'/' : $url;
    }
}
