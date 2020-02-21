<?php

namespace Helldar\LangTranslations\Console;

use Helldar\LangTranslations\Contracts\Lang;
use Helldar\LangTranslations\Services\ArrayLangService;
use Helldar\LangTranslations\Services\JsonLangService;
use Illuminate\Console\Command;
use Illuminate\Container\Container;

class Install extends Command
{
    protected $signature = 'lang-translations:install' .
    ' {lang* : Lang files to copy}' .
    ' {--f|force : Force replace lang files}' .
    ' {--j|json : Copy json files. If not specified, php files will be copied.}';

    protected $description = 'Install translations files.';

    public function handle()
    {
        $lang    = (array) $this->argument('lang');
        $force   = (bool) $this->option('force');
        $is_json = (bool) $this->option('json');

        $this->service($is_json)
            ->output($this->output)
            ->lang($lang)
            ->force($force)
            ->get();
    }

    protected function service(bool $is_json = false): Lang
    {
        return $is_json
            ? $this->app(JsonLangService::class)
            : $this->app(ArrayLangService::class);
    }

    protected function app(string $classname)
    {
        return Container::getInstance()
            ->make($classname);
    }
}
