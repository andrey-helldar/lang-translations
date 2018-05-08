<?php

namespace Helldar\LangTranslations\Console;

use Illuminate\Console\Command;

class LangTranslationsInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang-translations:install {lang* : Lang files to copy} {--f|force : Force replace lang files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install translations files.';

    /**
     * @var string
     */
    protected $path_src;

    /**
     * @var string
     */
    protected $path_dst;

    /**
     * @var array
     */
    protected $lang;

    /**
     * @var bool
     */
    protected $force = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->path_src = $this->formatPath('vendor/andrey-helldar/lang-translations/src/lang');
        $this->path_dst = $this->formatPath('resources/lang');

        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->lang  = $this->argument('lang');
        $this->force = (bool) $this->option('force');

        foreach ($this->lang as $lang) {
            $this->processLang($lang);
        }
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function formatPath($value)
    {
        return $this->finish(base_path($value), '/');
    }

    /**
     * Cap a string with a single instance of a given value.
     *
     * @param string $value
     * @param string $cap
     *
     * @return string
     */
    private function finish($value, $cap)
    {
        $quoted = preg_quote($cap, '/');

        return preg_replace('/(?:' . $quoted . ')+$/u', '', $value) . $cap;
    }

    /**
     * Make directory if not exists.
     *
     * @param $path
     */
    private function makeDir($path)
    {
        if (!file_exists($path)) {
            mkdir($path);
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
        $dir = $lang === 'en' ? '../script/en' : $lang;
        $src = $this->finish($this->path_src . $dir, '/');
        $dst = $this->finish($this->path_dst . $lang, '/');

        if (!file_exists($src)) {
            $this->error("The directory for the \"{$lang}\" language was not found");

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
        $src_files = scandir($src);

        foreach ($src_files as $file) {
            $src_file = ($src . $file);
            $dst_file = ($dst . $file);

            if (!is_file($src_file)) {
                continue;
            }

            if ($this->force || !file_exists($dst_file) || $this->confirm("Replace {$lang}/{$file} files?")) {
                $this->copy($src_file, $dst_file, ($lang . '/' . $file));
            }
        }
    }
}
