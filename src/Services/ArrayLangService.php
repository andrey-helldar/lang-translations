<?php

namespace Helldar\LangTranslations\Services;

use Helldar\Support\Facades\Directory;

use function file_exists;

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
     *
     * @throws \Helldar\PrettyArray\Exceptions\FileDoesntExistsException
     * @throws \Helldar\PrettyArray\Exceptions\UnknownCaseTypeException
     * @throws \Helldar\Support\Exceptions\DirectoryNotFoundException
     */
    protected function processFile(string $src, string $dst)
    {
        foreach (Directory::all($src) as $file) {
            if ($file->isDot() || ! $file->isFile()) {
                continue;
            }

            $dst_file = $dst . $file->getFilename();

            if ($this->force || ! file_exists($dst_file)) {
                $this->copy($file->getRealPath(), $dst_file, $file->getFilename());
            } else {
                $this->info("The target file \"{$file->getFilename()}\" exists.");
            }
        }
    }
}
