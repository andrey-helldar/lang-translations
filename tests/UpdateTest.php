<?php

namespace Tests;

class UpdateTest extends TestCase
{
    public function testMissingLang()
    {
        $this->artisan('lang-translations:update');

        $this->assertTrue(true);
    }

    public function testSuccess()
    {
        $this->artisan('lang-translations:update');

        $this->assertDirectoryExists($this->langPath('ru'));
        $this->assertFileExists($this->langPath('ru/auth.php'));
        $this->assertFileExists($this->langPath('ru/buttons.php'));
        $this->assertFileExists($this->langPath('ru/errors.php'));
        $this->assertFileExists($this->langPath('ru/forms.php'));
        $this->assertFileExists($this->langPath('ru/statuses.php'));
        $this->assertFileExists($this->langPath('ru/titles.php'));
    }

    public function testJson()
    {
        $this->artisan('lang-translations:update', ['--json' => true]);

        $this->assertFileExists($this->langPath('en.json'));
        $this->assertFileExists($this->langPath('ru.json'));
    }
}
