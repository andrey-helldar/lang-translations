<?php

namespace Helldar\LangTranslations\Services;

use Helldar\LangTranslations\Contracts\Lang;
use Helldar\PrettyArray\Contracts\Caseable;
use Helldar\PrettyArray\Services\File;
use Helldar\PrettyArray\Services\Formatter;
use Helldar\Support\Facades\Arr;
use Helldar\Support\Facades\File as FileSupport;
use Helldar\Support\Facades\Str;
use Illuminate\Console\OutputStyle;
use Symfony\Component\Console\Output\OutputInterface;

use function config;
use function file_exists;
use function ksort;
use function pathinfo;
use function resource_path;

abstract class BaseService implements Lang
{
    /** @var string */
    protected $path_src;

    /** @var string */
    protected $path_dst;

    /** @var array */
    protected $lang;

    /** @var bool */
    protected $force = false;

    /** @var bool */
    protected $is_json = false;

    /** @var array */
    protected $exclude = [];

    /** @var int */
    protected $case;

    /**
     * The output interface implementation.
     *
     * @var \Illuminate\Console\OutputStyle
     */
    protected $output;

    public function __construct()
    {
        $this->path_src = Str::finish(__DIR__ . '/../lang', DIRECTORY_SEPARATOR);
        $this->path_dst = Str::finish(resource_path('lang'), DIRECTORY_SEPARATOR);

        $this->exclude = config('lang-publisher.exclude', []);
        $this->case    = config('lang-publisher.case', Caseable::NO_CASE);
    }

    public function output(OutputStyle $output): Lang
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @param array $values
     *
     * @return $this
     */
    public function lang(array $values = []): Lang
    {
        $this->lang = $values;

        return $this;
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function force(bool $value = false): Lang
    {
        $this->force = $value;

        return $this;
    }

    /**
     * @param string $src
     * @param string $dst
     * @param string $filename
     *
     * @throws \Helldar\PrettyArray\Exceptions\FileDoesntExistsException
     * @throws \Helldar\PrettyArray\Exceptions\UnknownCaseTypeException
     */
    protected function copy(string $src, string $dst, string $filename)
    {
        $action = file_exists($dst) ? 'replaced' : 'copied';

        $source = $this->loadFile($src, true);
        $target = $this->loadFile($dst, true);

        $excluded = $this->excluded($dst, $target);

        $source = Arr::merge($target, $source, $excluded);

        $this->store($dst, $source);

        $this->info("File {$filename} successfully {$action}");
    }

    /**
     * @param string $string
     * @param string|null $style
     */
    protected function line(string $string, string $style = null)
    {
        $styled = $style ? "<$style>$string</$style>" : $string;

        $this->output->writeln($styled, OutputInterface::OUTPUT_NORMAL);
    }

    /**
     * @param string $string
     */
    protected function info(string $string)
    {
        $this->line($string, 'info');
    }

    /**
     * @param string $string
     */
    protected function error(string $string)
    {
        $this->line($string, 'error');
    }

    /**
     * Loading existence check file.
     *
     * @param string $filename
     * @param bool $return_empty
     *
     * @return array
     * @throws \Helldar\PrettyArray\Exceptions\FileDoesntExistsException
     *
     */
    protected function loadFile(string $filename, bool $return_empty = false): array
    {
        if ($return_empty && ! file_exists($filename)) {
            return [];
        }

        return File::make()->load($filename);
    }

    /**
     * Getting excluded keys.
     *
     * @param string $filename
     * @param array $array
     *
     * @return array
     */
    protected function excluded(string $filename, array $array): array
    {
        $filename = pathinfo($filename, PATHINFO_FILENAME);
        $keys     = $this->exclude[$filename] ?? [];

        return Arr::only($array, $keys);
    }

    protected function processLang(string $lang)
    {
        $src = Str::finish($this->path_src . $lang);
        $dst = Str::finish($this->path_dst . $lang);

        if (! file_exists($src)) {
            $this->error("The source directory for the \"{$lang}\" language was not found");

            return;
        }

        FileSupport::makeDirectory($dst);

        $this->processFile($src, $dst, $lang);
    }

    /**
     * Saving the resulting array to a file.
     *
     * @param string $path
     * @param array $array
     *
     * @throws \Helldar\PrettyArray\Exceptions\UnknownCaseTypeException
     */
    protected function store(string $path, array $array)
    {
        ksort($array);

        $service = Formatter::make();
        $service->setKeyAsString();
        $service->setCase($this->case);

        if (config('lang-publisher.alignment') === true) {
            $service->setEqualsAlign();
        }

        $content = $service->raw($array);

        File::make($content)->store($path);
    }
}
