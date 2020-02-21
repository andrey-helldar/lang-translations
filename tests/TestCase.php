<?php

namespace Tests;

use Helldar\LangTranslations\ServiceProvider;
use function implode;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function langPath(string $filename = ''): string
    {
        return $this->app->resourcePath(
            implode(DIRECTORY_SEPARATOR, ['lang', $filename])
        );
    }

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }
}
