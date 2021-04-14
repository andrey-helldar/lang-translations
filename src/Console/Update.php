<?php

namespace Helldar\LangTranslations\Console;

use Helldar\Support\Facades\Helpers\Filesystem\Directory;
use Illuminate\Console\Command;

class Update extends Command
{
    protected $signature = 'lang-translations:update {--j|json}';

    protected $description = 'Update translations files.';

    public function handle()
    {
        $this->install(
            $this->languages()
        );
    }

    protected function install($lang = 'en')
    {
        $this->call('lang-translations:install', [
            'lang'    => $lang,
            '--force' => true,
            '--json'  => $this->option('json'),
        ]);
    }

    protected function languages(): array
    {
        return Directory::names(
            $this->languagesPath()
        );
    }

    protected function languagesPath(): string
    {
        return resource_path('lang');
    }
}
