<?php

namespace Helldar\LangTranslations\Console;

use Helldar\LangTranslations\Services\ArrayLangService;
use Helldar\LangTranslations\Services\JsonLangService;
use Illuminate\Console\Command;

class Install extends Command
{
    protected $signature = 'lang-translations:install' .
    ' {lang* : Lang files to copy}' .
    ' {--f|force : Force replace lang files}' .
    ' {--j|json : Copy json files. If not specified, php files will be copied.}';

    protected $description = 'Install translations files.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $lang    = (array) $this->argument('lang');
        $force   = (bool) $this->option('force');
        $is_json = (bool) $this->option('json');

        $service = $is_json ? new JsonLangService : new ArrayLangService;

        $service
            ->output($this->output)
            ->lang($lang)
            ->force($force)
            ->get();
    }
}
