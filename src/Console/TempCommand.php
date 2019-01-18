<?php

namespace Helldar\LangTranslations\Console;

use Helldar\LangTranslations\Services\ArrayLangService;
use Helldar\LangTranslations\Services\JsonLangService;
use Illuminate\Console\Command;

class TempCommand extends Command
{
    protected $signature = 'temp';

    protected $description = 'temp';

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
            ->lang($lang)
            ->force($force)
            ->get();
    }
}
