<?php

namespace Helldar\LangTranslations\Services;

use Helldar\LangTranslations\Interfaces\LangServiceInterface;

abstract class BaseService implements LangServiceInterface
{
    /** @var string */
    protected $path_src;

    /** @var string */
    protected $path_dst;

    /** @var array */
    protected $lang;

    /** @var bool */
    protected $force = false;

    /** @var bool */
    protected $is_json = false;

    public function __construct()
    {
        $this->path_src = str_finish(__DIR__ . '/../lang', '/');
        $this->path_dst = str_finish(resource_path('lang'), '/');
    }

    /**
     * @param array $values
     *
     * @return $this
     */
    public function lang($values = [])
    {
        $this->lang = (array) $values;

        return $this;
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function force($value = false)
    {
        $this->force = (bool) $value;

        return $this;
    }

    protected function makeDir($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }
    }
}
