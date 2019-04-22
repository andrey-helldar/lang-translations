<?php

namespace Helldar\LangTranslations;

use Helldar\LangTranslations\Console\Install;
use Helldar\LangTranslations\Console\Update;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $defer = false;

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Install::class,
                Update::class,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        //
    }
}
