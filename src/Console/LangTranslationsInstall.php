<?php

namespace Helldar\LangTranslations\Console;

use Illuminate\Console\Command;

class LangTranslationsInstall extends Command
{
    /** @var string */
    protected $signature = 'lang-translations:install' .
    ' {lang* : Lang files to copy}' .
    ' {--f|force : Force replace lang files}' .
    ' {--j|json : Copy json files. If not specified, php files will be copied.}';

    /** @var string */
    protected $description = 'Install translations files.';

    /** @var string */
    protected $path_src;

    /** @var string */
    protected $path_dst;

    /** @var */
    protected $lang;

    /** @var bool */
    protected $force = false;

    /** @var bool */
    protected $is_json = false;

    /** @var string */
    protected $extension;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->path_src = str_finish(__DIR__ . '/../lang', '/');
        $this->path_dst = str_finish(resource_path('lang'), '/');

        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->lang      = $this->argument('lang');
        $this->force     = (bool) $this->option('force');
        $this->is_json   = (bool) $this->option('json');
        $this->extension = $this->option('json') ? '*.json' : '*.php';

        foreach ($this->lang as $lang) {
            $this->processLang($lang);
        }
    }

    /**
     * Make directory if not exists.
     *
     * @param string $path
     */
    private function makeDir(string $path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }
    }

    /**
     * @param string $src
     * @param string $dst
     * @param string $filename
     */
    private function copy($src, $dst, $filename)
    {
        $action_copy    = file_exists($dst) ? 'replaced' : 'copied';
        $action_replace = file_exists($dst) ? 'replaced' : 'copied';

        if (copy($src, $dst)) {
            $this->info("File {$filename} successfully {$action_copy}");

            return;
        }

        $this->error("Error {$action_replace} {$filename} file");
    }

    /**
     * @param string $lang
     */
    private function processLang($lang)
    {
        $src = str_finish($this->path_src, '/');
        $dst = str_finish($this->path_dst, '/');

        if (!$this->is_json) {
            $src .= str_finish($lang, '/');
            $dst .= str_finish($lang, '/');
        }

        if (!file_exists($src)) {
            $this->error("The source directory for the \"{$lang}\" language was not found");

            return;
        }

        $this->makeDir($dst);
        $this->processFile($src, $dst, $lang);
    }

    /**
     * @param string $src
     * @param string $dst
     * @param string $lang
     */
    private function processFile($src, $dst, $lang)
    {
        $src_path = $src . $this->extension;

        foreach (glob($src_path) as $src_file) {
            $filename = pathinfo($src_file, PATHINFO_FILENAME);

            if (!is_file($src_file) || ($filename !== $lang && $this->is_json)) {
                continue;
            }

            $basename = pathinfo($src_file, PATHINFO_BASENAME);
            $dst_file = ($dst . $basename);

            if ($this->force || !file_exists($dst_file) || $this->confirm("Replace {$lang}/{$basename} files?")) {
                $this->copy($src_file, $dst_file, ($lang . '/' . $basename));
            }
        }
    }
}
