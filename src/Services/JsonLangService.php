<?php

namespace Helldar\LangTranslations\Services;

use Helldar\Support\Facades\Arr;
use Helldar\Support\Facades\Str;

use function file_exists;
use function file_get_contents;
use function glob;
use function is_file;
use function is_null;
use function json_decode;
use function ksort;
use function resource_path;
use function sprintf;

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
     * @throws \Helldar\PrettyArray\Exceptions\UnknownCaseTypeException
     */
    public function get()
    {
        $this->getTransKeys();
        $this->loadResult();

        foreach ($this->lang as $lang) {
            $this->processLang($lang);
        }
    }

    /**
     * @param $lang
     *
     * @throws \Helldar\PrettyArray\Exceptions\FileDoesntExistsException
     * @throws \Helldar\PrettyArray\Exceptions\UnknownCaseTypeException
     */
    protected function processLang($lang)
    {
        $src = Str::finish($this->path_src . $lang);
        $dst = Str::finish($this->path_dst);

        if (! file_exists($src)) {
            $this->error("The source directory for the \"{$lang}\" language was not found");

            return;
        }

        $this->processFile($src, $dst, $lang);
    }

    /**
     * @param string $src
     * @param string $dst
     * @param string $lang
     *
     * @throws \Helldar\PrettyArray\Exceptions\FileDoesntExistsException
     * @throws \Helldar\PrettyArray\Exceptions\UnknownCaseTypeException
     */
    protected function processFile($src, $dst, $lang)
    {
        $src_path = $src . '*.php';
        $dst_path = $dst . $lang . '.json';

        foreach (glob($src_path) as $src_file) {
            if (! is_file($src_file)) {
                continue;
            }

            $target = [];

            if (file_exists($dst_path)) {
                $content = file_get_contents($dst_path);
                $target  = json_decode($content, true);
            }

            $source = $this->loadFile($src_file, true);

            $this->putSource($target, $this->trans_keys);
            $this->putSource($source, $this->trans_keys);
        }

        $this->store($dst, $lang);
    }

    /**
     * @throws \Helldar\PrettyArray\Exceptions\FileDoesntExistsException
     */
    protected function getTransKeys()
    {
        $src_path = sprintf('%s%s/*.php', $this->path_src, $this->default_lang);

        foreach (glob($src_path) as $src_file) {
            $items = $this->loadFile($src_file);

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

    protected function store(string $dst, array $lang)
    {
        $action   = file_exists($dst) ? 'replaced' : 'copied';
        $filename = $lang . '.json';
        $dst_path = $dst . $filename;

        if (! $this->force) {
            $this->error("File {$filename} already exists!");

            return;
        }

        ksort($this->result);

        Arr::storeAsJson($this->result, $dst_path, true);

        $this->info("File {$filename} successfully {$action}");
    }
}
