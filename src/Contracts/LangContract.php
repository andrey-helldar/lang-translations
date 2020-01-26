<?php

namespace Helldar\LangTranslations\Contracts;

use Illuminate\Console\OutputStyle;

interface LangContract
{
    public function output(OutputStyle $output);

    public function lang(array $values = []);

    public function get();
}
