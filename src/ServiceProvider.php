<?php

namespace Helldar\LangTranslations;

use Helldar\LangTranslations\Console\LangTranslationsInstall;
use Helldar\LangTranslations\Console\LangTranslationsUpdate;

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
                LangTranslationsInstall::class,
                LangTranslationsUpdate::class,
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
