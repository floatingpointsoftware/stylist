<?php

namespace FloatingPoint\Stylist\Console;

use Stylist;
use Illuminate\Console\Command;
use FloatingPoint\Stylist\Theme\Theme;

class BowerInstallCommand extends Command
{
    const EXIT_COMMAND_NOT_FOUND = 127;
    const EXIT_SUCCESS = 0;

    /**
     * @var string
     */
    protected $signature = 'stylist:bower-install {--production}';

    /**
     * @var string
     */
    protected $description = 'Runs `bower install` on all themes, where applicable';

    public function fire()
    {
        if (!$this->isBowerInstalled()) return;

        foreach(Stylist::themes() as $theme) {
            $this->handleThemeBowerInstall($theme);
        }
    }


    protected function handleThemeBowerInstall(Theme $theme)
    {
        $path = $theme->getPath();
        if (!$this->bowerFileExists($path)) {
            return $this->info("Skipping bower install for theme '{$theme->getName()}': bower.json file not found. ");
        }

        $this->info("Installing bower components for theme '{$theme->getName()}'");

        $result = null;
        passthru($this->getExecCommand($path), $result);

        if ($result !== self::EXIT_SUCCESS) {
            $this->error('Bower install ended with error!');
        }
    }

    /**
     * @return boolean
     */
    protected function isBowerInstalled()
    {
        $result = null;
        $output = [];
        exec('bower', $output, $result);

        if ($result === self::EXIT_COMMAND_NOT_FOUND) {
            $this->error('Bower is not installed, or could not be found.');
            return false;
        } elseif ($result !== self::EXIT_SUCCESS) {
            $this->error('An error occurred while checking Bower installation: ' . implode(PHP_EOL, $output));
            return false;
        }

        return true;
    }

    /**
     * @param $path
     * @return string
     */
    protected function getExecCommand($path)
    {
        $command = "cd {$path} && bower install";

        if ($this->option('production')) {
            $command .= ' --production';
        }

        return $command;
    }

    /**
     * @param $path
     * @return bool
     */
    protected function bowerFileExists($path)
    {
        return file_exists($path . '/bower.json');
    }
}
