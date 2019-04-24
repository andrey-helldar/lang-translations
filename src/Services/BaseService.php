<?php

namespace Helldar\LangTranslations\Services;

use Helldar\LangTranslations\Interfaces\LangServiceInterface;
use Helldar\Support\Facades\Arr;
use Helldar\Support\Facades\Str;
use Illuminate\Console\OutputStyle;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseService implements LangServiceInterface
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

    /**
     * The output interface implementation.
     *
     * @var \Illuminate\Console\OutputStyle
     */
    protected $output;

    public function __construct()
    {
        $this->path_src = Str::finish(__DIR__ . '/../lang', DIRECTORY_SEPARATOR);
        $this->path_dst = Str::finish(\resource_path('lang'), DIRECTORY_SEPARATOR);
    }

    public function output(OutputStyle $output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @param array $values
     *
     * @return $this
     */
    public function lang(array $values = [])
    {
        $this->lang = $values;

        return $this;
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function force(bool $value = false)
    {
        $this->force = $value;

        return $this;
    }

    protected function copy($src, $dst, $filename)
    {
        $action = \file_exists($dst) ? 'replaced' : 'copied';

        $source = \file_exists($src) ? require $src : [];
        $target = \file_exists($dst) ? require $dst : [];

        $source = Arr::merge($target, $source);

        Arr::storeAsArray($source, $dst, true);

        $this->info("File {$filename} successfully {$action}");
    }

    protected function line($string, $style = null)
    {
        $styled = $style ? "<$style>$string</$style>" : $string;

        $this->output->writeln($styled, OutputInterface::OUTPUT_NORMAL);
    }

    protected function info($string)
    {
        $this->line($string, 'info');
    }

    protected function error($string)
    {
        $this->line($string, 'error');
    }
}
