<?php

namespace App\Support;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput as BaseConsoleOutput;

class ConsoleOutput extends BaseConsoleOutput
{
    /**
     * Creates a better format for a info output.
     *
     * @param  string $text
     * @return void
     */
    public function info($message) {
        $style = new OutputFormatterStyle('green');
        $this->getFormatter()->setStyle('info', $style);
        $this->writeln("<info> > $message</>");
    }

    /**
     * Creates a better format for a success output.
     *
     * @param  string $text
     * @return void
     */
    public function success($message)
    {
        $style = new OutputFormatterStyle('black', 'green');
        $this->getFormatter()->setStyle('success', $style);
        $this->writeln('');
        $this->writeln("<success>[SUCCESS]</> $message");
    }
}
