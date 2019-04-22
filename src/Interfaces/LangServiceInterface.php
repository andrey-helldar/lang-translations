<?php

namespace Helldar\LangTranslations\Interfaces;

use Illuminate\Console\OutputStyle;

interface LangServiceInterface
{
    public function output(OutputStyle $output);

    public function lang(array $values = []);

    public function get();
}
