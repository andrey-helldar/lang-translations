<?php

namespace Helldar\LangTranslations\Services;

use Helldar\LangTranslations\Interfaces\LangServiceInterface;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Str;
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
        $this->path_dst = Str::finish(resource_path('lang'), DIRECTORY_SEPARATOR);
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
    public function lang($values = [])
    {
        $this->lang = (array) $values;

        return $this;
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function force($value = false)
    {
        $this->force = (bool) $value;

        return $this;
    }

    protected function copy($src, $dst, $filename)
    {
        $action = file_exists($dst) ? 'replaced' : 'copied';

        if (copy($src, $dst)) {
            $this->info("File {$filename} successfully {$action}");

            return;
        }

        $this->error("Error {$action} {$filename} file");
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
