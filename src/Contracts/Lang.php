<?php

namespace Helldar\LangTranslations\Contracts;

use Illuminate\Console\OutputStyle;

interface Lang
{
    public function output(OutputStyle $output): self;

    public function lang(array $values = []): self;

    public function force(bool $value = false): self;

    public function get();
}
