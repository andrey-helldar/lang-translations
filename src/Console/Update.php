<?php

namespace Helldar\LangTranslations\Console;

use function array_filter;
use Helldar\Support\Facades\Str;

use Illuminate\Console\Command;
use function in_array;
use function is_file;
use function resource_path;
use function scandir;

class Update extends Command
{
    protected $signature = 'lang-translations:update {--j|json}';

    protected $description = 'Update translations files.';

    public function handle()
    {
        $lang = $this->getLangDirectories();

        $this->install($lang);
    }

    protected function getLangDirectories(): array
    {
        $path = resource_path('lang');
        $path = Str::finish($path, DIRECTORY_SEPARATOR);
        $dir  = scandir($path);

        return array_filter($dir, function ($item) use ($path) {
            if (is_file($path . $item)) {
                return false;
            }

            return ! in_array($item, ['.', '..', 'vendor']);
        });
    }

    protected function install($lang = 'en')
    {
        $this->call('lang-translations:install', [
            'lang'    => $lang,
            '--force' => true,
            '--json'  => $this->option('json'),
        ]);
    }
}
