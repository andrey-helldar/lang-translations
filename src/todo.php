<?php

class TodoGenerator
{
    /** @var string */
    protected $base_path;

    /** @var string */
    protected $default_lang = 'en';

    /** @var array */
    protected $trans = [];

    /** @var array */
    protected $languages = [];

    /** @var array */
    protected $output = [];

    public function __construct($base_path)
    {
        $this->base_path = $this->finish($base_path, '/');

        $this->loadDefaultLanguage();
        $this->loadLanguages();
        $this->getTranslations();
    }

    public static function init($base_path)
    {
        return new self($base_path);
    }

    public function save($path)
    {
        file_put_contents($path, $this->getOutput());
    }

    private function loadLanguages()
    {
        $dir = scandir($this->base_path);

        $this->languages = array_filter($dir, function ($item) {
            if (is_file($this->base_path)) {
                return false;
            }

            return !in_array($item, ['.', '..', 'vendor', $this->default_lang]);
        });
    }

    private function loadDefaultLanguage()
    {
        $path     = realpath($this->base_path . $this->default_lang);
        $src_path = $this->finish($path, '/') . '*';

        foreach (glob($src_path) as $src_file) {
            if (!is_file($src_file)) {
                continue;
            }

            $filename = pathinfo($src_file, PATHINFO_FILENAME);
            $trans    = include $src_file;

            $this->trans[$filename] = $trans;
        }
    }

    private function getTranslations()
    {
        foreach ($this->languages as $language) {
            $src_path = $this->base_path . $language . '/*.php';

            foreach (glob($src_path) as $src_file) {
                if (!is_file($src_file)) {
                    continue;
                }

                $filename = pathinfo($src_file, PATHINFO_FILENAME);
                $trans    = include $src_file;

                $this->compare($language, $filename, $trans);
            }
        }
    }

    private function compare($language, $filename, $trans = [])
    {
        foreach ($this->trans[$filename] as $default_key => $default_value) {
            if (!array_key_exists($default_key, $trans)) {
                $this->output[$language][$filename][$default_key] = $default_value;

                continue;
            }

            if ($default_value === $trans[$default_key]) {
                $this->output[$language][$filename][$default_key] = $default_value;
            }
        }
    }

    private function getOutput()
    {
        $output = "# Todo list\n\n";

        // Make menu
        $count   = sizeof($this->output);
        $columns = $count < 12 ? $count : 12;

        $captions    = implode('|', array_fill(0, $columns, ' '));
        $subcaptions = implode('|', array_fill(0, $columns, ':---:'));

        $output .= "|$captions|\n";
        $output .= "|$subcaptions|\n";

        $menu = [];
        foreach (array_keys($this->output) as $language) {
            $menu[] = "[$language](#$language)";
        }

        $rows = array_chunk($menu, $columns);
        array_map(function ($row) use (&$output) {
            $row    = implode('|', $row);
            $output .= $row . "\n";
        }, $rows);

        $output .= "\n\n";

        // Make items
        foreach ($this->output as $language => $section) {
            $output .= "## {$language}:\n";

            foreach ($section as $key => $values) {
                $output .= "#### {$key}\n";

                foreach ($values as $lang_key => $lang_value) {
                    $output .= "* $lang_key : $lang_value\n";
                }

                $output .= "\n";
            }

            $output .= "\n\n[ [to top](#todo-list) ]\n\n";
        }

        return $output;
    }

    private function finish($value, $cap)
    {
        $quoted = preg_quote($cap, '/');

        return preg_replace('/(?:' . $quoted . ')+$/u', '', $value) . $cap;
    }
}

TodoGenerator::init(__DIR__ . '/lang/')
    ->save(__DIR__ . '/../TODO.md');
