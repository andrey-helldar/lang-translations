<?php

namespace Helldar\LangTranslations\Services;

use Helldar\Support\Facades\Arr;

use function compact;
use function file_exists;
use function glob;
use function implode;
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
                $this->mirrorForms($src_file, $lang);
            } else {
                $this->info("The target file \"{$filename}\" exists.");
            }
        }
    }

    /**
     * @param string $src
     *
     * @param string $lang
     *
     * @throws \Helldar\PrettyArray\Exceptions\FileDoesntExistsException
     * @throws \Helldar\PrettyArray\Exceptions\UnknownCaseTypeException
     */
    protected function mirrorForms(string $src, string $lang)
    {
        if (pathinfo($src, PATHINFO_FILENAME) === 'forms') {
            $dst = implode(DIRECTORY_SEPARATOR, [$this->path_dst, $lang, 'validation.php']);

            $attributes = $this->loadFile($src, true);
            $target     = $this->loadFile($dst, true);

            $target = Arr::merge($target, compact('attributes'));

            $this->store($dst, $target);

            $this->info("Form attributes successfully mirrored for \"{$lang}\"");
        }
    }
}
