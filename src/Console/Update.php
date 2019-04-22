<?php

namespace Helldar\LangTranslations\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang-translations:update {--j|json}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update translations files.';

    public function handle()
    {
        $lang = $this->getLangDirectories();
        $this->install($lang);
    }

    /**
     * @return array
     */
    private function getLangDirectories()
    {
        $path = resource_path('lang');
        $path = Str::finish($path, DIRECTORY_SEPARATOR);
        $dir  = scandir($path);

        return array_filter($dir, function ($item) use ($path) {
            if (is_file($path . $item)) {
                return false;
            }

            return !in_array($item, ['.', '..', 'vendor']);
        });
    }

    private function install($lang = 'en')
    {
        $this->call('lang-translations:install', [
            'lang'    => $lang,
            '--force' => true,
            '--json'  => $this->option('json'),
        ]);
    }
}
