<?php

namespace Helldar\LangTranslations\Services;

use Helldar\Support\Facades\Helpers\Arr;
use Helldar\Support\Facades\Helpers\Filesystem\Directory;

class JsonLangService extends BaseService
{
    /** @var bool */
    protected $is_json = true;

    /** @var string */
    protected $default_lang = 'en';

    protected $trans_keys = [];

    protected $result = [];

    /**
     * @throws \Helldar\PrettyArray\Exceptions\FileDoesntExistsException
     * @throws \Helldar\Support\Exceptions\DirectoryNotFoundException
     */
    public function get()
    {
        $this->getTransKeys();
        $this->loadResult();

        foreach ($this->lang as $lang) {
            $this->processLang($lang, true);
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
        $dst_path = $dst . $lang . '.json';

        foreach (Directory::all($src) as $file) {
            if ($file->isDot() || ! $file->isFile()) {
                continue;
            }

            $target = [];

            if (file_exists($dst_path)) {
                $content = file_get_contents($dst_path);
                $target  = json_decode($content, true);
            }

            $source = $this->loadFile($file->getRealPath(), true);

            $this->putSource($target, $this->trans_keys);
            $this->putSource($source, $this->trans_keys);
        }

        $this->store($dst_path, $this->result);
    }

    /**
     * @throws \Helldar\PrettyArray\Exceptions\FileDoesntExistsException
     * @throws \Helldar\Support\Exceptions\DirectoryNotFoundException
     */
    protected function getTransKeys()
    {
        $path = $this->path_src . $this->default_lang;

        foreach (Directory::all($path) as $file) {
            if ($file->isDot() || ! $file->isFile()) {
                continue;
            }

            $items = $this->loadFile($file->getRealPath());

            $this->merge($this->trans_keys, $items);
        }
    }

    protected function loadResult()
    {
        foreach ($this->lang as $lang) {
            $path = resource_path("lang/{$lang}.json");

            if (file_exists($path)) {
                $content = file_get_contents($path);
                $array   = json_decode($content, true);

                foreach ($array as $key => $value) {
                    $this->put($key, $key);
                }
            }
        }
    }

    protected function putSource(array $array, array $keys)
    {
        foreach ($array as $key => $value) {
            $key   = $keys[$key] ?? null;
            $value = $value ?: $key;

            $this->put($key, $value);
        }
    }

    protected function put($key = null, $value = null)
    {
        if (! is_null($key) && ! is_null($value)) {
            $this->result[$key] = $value;
        }
    }

    protected function merge(array &$source, $array = [])
    {
        $source = Arr::merge($source, $array);
    }

    protected function store(string $dst, array $array)
    {
        $filename = pathinfo($dst, PATHINFO_BASENAME);

        if ($this->force || ! file_exists($dst)) {
            Arr::storeAsJson($dst, $array, true);

            $action = file_exists($dst) ? 'replaced' : 'copied';

            $this->info("File {$filename} successfully {$action}");

            return;
        }

        $this->error("File {$filename} doesn't exists.");
    }
}
