<?php

namespace Tests;

use Symfony\Component\Console\Exception\RuntimeException;

class InstallTest extends TestCase
{
    public function testMissingLang()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('missing: "lang"');

        $this->artisan('lang-translations:install');
    }

    public function testFailedLang()
    {
        $this->artisan('lang-translations:install', ['lang' => 'foo']);

        $this->assertTrue(true);
    }

    public function testSuccess()
    {
        $this->artisan('lang-translations:install', ['lang' => 'ru']);

        $this->assertDirectoryExists($this->langPath('ru'));
        $this->assertFileExists($this->langPath('ru/auth.php'));
        $this->assertFileExists($this->langPath('ru/buttons.php'));
        $this->assertFileExists($this->langPath('ru/errors.php'));
        $this->assertFileExists($this->langPath('ru/forms.php'));
        $this->assertFileExists($this->langPath('ru/statuses.php'));
        $this->assertFileExists($this->langPath('ru/titles.php'));
    }

    public function testNotInstalled()
    {
        $this->assertDirectoryDoesNotExist($this->langPath('de'));
        $this->assertFileDoesNotExist($this->langPath('de/auth.php'));
        $this->assertFileDoesNotExist($this->langPath('de/buttons.php'));
        $this->assertFileDoesNotExist($this->langPath('de/errors.php'));
        $this->assertFileDoesNotExist($this->langPath('de/forms.php'));
        $this->assertFileDoesNotExist($this->langPath('de/statuses.php'));
        $this->assertFileDoesNotExist($this->langPath('de/titles.php'));
    }

    public function testReinstall()
    {
        $this->artisan('lang-translations:install', ['lang' => 'ru']);

        $this->assertTrue(true);
    }

    public function testForceInstall()
    {
        $this->artisan('lang-translations:install', ['lang' => 'ru', '--force' => true]);

        $this->assertTrue(true);
    }

    public function testJson()
    {
        $this->artisan('lang-translations:install', ['lang' => 'ca', '--json' => true]);

        $this->assertFileExists($this->langPath('ca.json'));
    }
}
