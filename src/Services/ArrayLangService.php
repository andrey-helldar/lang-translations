<?php

namespace Helldar\LangTranslations\Services;

use function array_map;
use function file_exists;

use function glob;
use Helldar\Support\Facades\File;
use Helldar\Support\Facades\Str;
use function is_file;
use function pathinfo;

class ArrayLangService extends BaseService
{
    public function get()
    {
        array_map(function ($lang) {
            $this->processLang($lang);
        }, $this->lang);
    }

    /**
     * @param $lang
     *
     * @throws \Helldar\PrettyArray\Exceptions\FileDoesntExistsException
     */
    protected function processLang($lang)
    {
        $src = Str::finish($this->path_src . $lang);
        $dst = Str::finish($this->path_dst . $lang);

        if (! file_exists($src)) {
            $this->error("The source directory for the \"{$lang}\" language was not found");

            return;
        }

        File::makeDirectory($dst);

        $this->processFile($src, $dst, $lang);
    }

    /**
     * @param $src
     * @param $dst
     * @param $lang
     *
     * @throws \Helldar\PrettyArray\Exceptions\FileDoesntExistsException
     */
    protected function processFile($src, $dst, $lang)
    {
        $src_path = $src . '*.php';

        foreach (glob($src_path) as $src_file) {
            $basename = pathinfo($src_file, PATHINFO_BASENAME);
            $filename = $lang . DIRECTORY_SEPARATOR . $basename;
            $dst_file = $dst . $basename;

            if (! is_file($src_file)) {
                continue;
            }

            if ($this->force || ! file_exists($dst_file)) {
                $this->copy($src_file, $dst_file, $filename);
            } else {
                $this->info("The target file \"{$filename}\" exists.");
            }
        }
    }
}
