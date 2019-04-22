<?php

namespace Helldar\LangTranslations\Services;

use Helldar\Support\Facades\Arr;
use Helldar\Support\Facades\Str;

class JsonLangService extends BaseService
{
    /** @var bool */
    protected $is_json = true;

    /** @var string */
    protected $default_lang = 'en';

    protected $trans_keys = [];

    protected $result = [];

    public function get()
    {
        $this->getTransKeys();
        $this->loadResult();

        foreach ($this->lang as $lang) {
            $this->processLang($lang);
        }
    }

    private function processLang($lang)
    {
        $src = Str::finish($this->path_src . $lang);
        $dst = Str::finish($this->path_dst);

        if (!\file_exists($src)) {
            $this->error("The source directory for the \"{$lang}\" language was not found");

            return;
        }

        $this->processFile($src, $dst, $lang);
    }

    private function processFile($src, $dst, $lang)
    {
        $src_path = $src . '*.php';
        $dst_path = $dst . $lang . '.json';

        foreach (\glob($src_path) as $src_file) {
            if (!\is_file($src_file)) {
                continue;
            }

            $basename = \pathinfo($src_file, PATHINFO_FILENAME);
            $target   = [];

            if (\file_exists($dst_path)) {
                $content = \file_get_contents($dst_path);
                $target  = \json_decode($content, true);
            }

            $source = \file_exists($src_file) ? require $src_file : [];

            $this->putSource($target, $this->trans_keys);
            $this->putSource($source, $this->trans_keys);
        }

        $this->store($dst, $lang);
    }

    private function getTransKeys()
    {
        $src_path = \sprintf('%s%s/*.php', $this->path_src, $this->default_lang);

        foreach (\glob($src_path) as $src_file) {
            $items = require $src_file;

            $this->merge($this->trans_keys, $items);
        }
    }

    private function loadResult()
    {
        foreach ($this->lang as $lang) {
            $path = \resource_path("lang/{$lang}.json");

            if (\file_exists($path)) {
                $content = \file_get_contents($path);
                $array   = \json_decode($content, true);

                foreach ($array as $key => $value) {
                    $this->put($key, $key);
                }
            }
        }
    }

    private function putSource(array $array, array $keys)
    {
        foreach ($array as $key => $value) {
            $key   = $keys[$key] ?? null;
            $value = $value ?: $key;

            $this->put($key, $value);
        }
    }

    private function put($key = null, $value = null)
    {
        if (!\is_null($key) && !\is_null($value)) {
            $this->result[$key] = $value;
        }
    }

    private function merge(array &$source, $array = [])
    {
        foreach ($array as $key => $value) {
            $source[$key] = $value;
        }
    }

    private function store($dst, $lang)
    {
        $action   = \file_exists($dst) ? 'replaced' : 'copied';
        $filename = $lang . '.json';
        $dst_path = $dst . $filename;

        if (!$this->force) {
            $this->error("File {$filename} already exists!");

            return;
        }

        \ksort($this->result);

        Arr::storeAsJson($this->result, $dst_path);

        $this->info("File {$filename} successfully {$action}");
    }
}
