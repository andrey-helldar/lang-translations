<?php

namespace Helldar\LangTranslations\Services;

use function file_exists;
use function glob;
use function is_file;
use function pathinfo;

class ArrayLangService extends BaseService
{
    public function get()
    {
        foreach ($this->lang as $lang) {
            $this->processLang($lang);
        }
    }

    /**
     * @param string $src
     * @param string $dst
     * @param string $lang
     *
     * @throws \Helldar\PrettyArray\Exceptions\FileDoesntExistsException
     * @throws \Helldar\PrettyArray\Exceptions\UnknownCaseTypeException
     */
    protected function processFile(string $src, string $dst, string $lang)
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
