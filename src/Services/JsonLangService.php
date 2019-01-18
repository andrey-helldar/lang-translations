<?php

namespace Helldar\LangTranslations\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class JsonLangService extends BaseService
{
    /** @var bool */
    protected $is_json = true;

    /** @var string */
    protected $default_lang = 'en';

    /** @var \Illuminate\Support\Collection */
    protected $trans_keys;

    /** @var \Illuminate\Support\Collection */
    protected $result;

    public function __construct()
    {
        parent::__construct();

        $this->trans_keys = new Collection;
        $this->result     = new Collection;
    }

    public function get()
    {
        $this->getTransKeys();

        foreach ($this->lang as $lang) {
            $this->processLang($lang);
        }
    }

    private function processLang($lang)
    {
        $src = Str::finish($this->path_src . $lang, DIRECTORY_SEPARATOR);
        $dst = Str::finish($this->path_dst, DIRECTORY_SEPARATOR);

        if (!file_exists($src)) {
            $this->error("The source directory for the \"{$lang}\" language was not found");

            return;
        }

        $this->processFile($src, $dst, $lang);
    }

    private function processFile($src, $dst, $lang)
    {
        $src_path = $src . '*.php';

        foreach (glob($src_path) as $src_file) {
            if (!is_file($src_file)) {
                continue;
            }

            $src_file = realpath($src_file);
            $trans    = include($src_file);

            foreach ($trans as $key => $value) {
                $key = $this->trans_keys->get($key);
                $this->put($key, $value);
            }
        }

        $this->store($dst, $lang);
    }

    private function getTransKeys()
    {
        $src_path = sprintf('%s%s/*.php', $this->path_src, $this->default_lang);

        foreach (glob($src_path) as $src_file) {
            $items = include $src_file;

            $this->merge($this->trans_keys, $items);
        }
    }

    private function put($key, $value)
    {
        $this->result->put($key, $value);
    }

    private function merge(Collection &$collection, $array = [])
    {
        foreach ($array as $key => $value) {
            $collection->put($key, $value);
        }
    }

    private function store($dst, $lang)
    {
        $action   = file_exists($dst) ? 'replaced' : 'copied';
        $filename = $lang . '.json';
        $dst_path = $dst . $filename;

        if (file_exists($dst_path) || !$this->force) {
            $this->error("Error {$action} {$filename} file");
        }

        $items = $this->result->toArray();

        ksort($items);

        file_put_contents($dst_path, json_encode($items));

        $this->result = new Collection;

        $this->info("File {$filename} successfully {$action}");
    }
}
