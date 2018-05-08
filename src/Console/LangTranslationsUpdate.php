<?php

namespace Helldar\LangTranslations\Console;

use Illuminate\Console\Command;

class LangTranslationsUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang-translations:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update translations files.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
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
        $dir = scandir(resource_path('lang'));

        return array_filter($dir, function ($item) {
            return !in_array($item, ['.', '..']);
        });
    }

    private function install($lang = 'en')
    {
        $this->call('lang-translations:install', [
            'lang' => $lang,
            '--force' => true,
        ]);
    }
}
