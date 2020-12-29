<?php

namespace Helldar\LangTranslations\Services;

use Helldar\Support\Facades\Helpers\Arr;
use Helldar\Support\Facades\Helpers\Filesystem\Directory;
use Helldar\Support\Facades\Helpers\Str;

class ArrayLangService extends BaseService
{
    public function get()
    {
        foreach ($this->lang as $lang) {
            $this->processLang($lang);
        }
    }

    /**
     * @param  string  $src
     * @param  string  $dst
     * @param  string  $lang
     *
     * @throws \Helldar\PrettyArray\Exceptions\FileDoesntExistsException
     * @throws \Helldar\PrettyArray\Exceptions\UnknownCaseTypeException
     */
    protected function processFile(string $src, string $dst, string $lang)
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

        $this->mirrorAttributes($lang);
    }

    /**
     * @param  string  $lang
     *
     * @throws \Helldar\PrettyArray\Exceptions\FileDoesntExistsException
     * @throws \Helldar\PrettyArray\Exceptions\UnknownCaseTypeException
     */
    protected function mirrorAttributes(string $lang)
    {
        $src = Str::finish($this->path_src . $lang) . 'forms.php';
        $dst = Str::finish($this->path_dst . $lang) . 'validation.php';

        $source = $this->loadFile($src, true);
        $target = $this->loadFile($dst, true);

        $target = Arr::merge($target, ['attributes' => $source]);

        $this->store($dst, $target);

        $this->info("Attributes successfully mirrored for the {$lang}.");
    }
}
