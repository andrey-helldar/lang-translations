<?php

namespace Helldar\LangTranslations\Services;

use Helldar\LangTranslations\Exceptions\HandlerException;
use Illuminate\Support\Str;

class ArrayLangService extends BaseService
{
    public function get()
    {
        foreach ($this->lang as $lang) {
            $this->process($lang);
        }
    }

    private function process($lang)
    {
        $src = Str::finish($this->path_src . $lang, '/');
        $dst = Str::finish($this->path_dst . $lang, '/');

        if (!file_exists($src)) {
            throw new HandlerException("The source directory for the \"{$lang}\" language was not found");
        }

        $this->makeDir($dst);
        // todo: stop point
    }
}
