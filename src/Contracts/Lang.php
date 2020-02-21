<?php

namespace Helldar\LangTranslations\Contracts;

use Illuminate\Console\OutputStyle;

interface Lang
{
    public function output(OutputStyle $output);

    public function lang(array $values = []);

    public function force(bool $value = false);

    public function get();
}
