<?php

namespace App\Console;

use App\Models\User\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class BaseCommand extends Command
{
    /**
     * Create a new extendable command instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Login as admin when running commands.
        $this->authenticate();

        // Set default language since it is not defined when running commands.
        app()->setLocale(config('app.fallback_locale'));

        parent::__construct();
    }

    /**
     * Authenticate current user as admin.
     *
     * @return void
     */
    protected function authenticate()
    {
        if (Schema::hasTable('users') && $admin = User::where('username', config('auth.admin.username'))->first()) {
            Auth::login($admin);
        }
    }

    /**
     * Wrapper for line function which preprends an empty line.
     *
     * @param  string $text
     * @return void
     */
    public function newline($text = '')
    {
        $this->output->newline();
        if (! $text == '') {
            $this->line($text);
        }
    }

    /**
     * Creates a better format for a success output.
     *
     * @param  string $text
     * @return void
     */
    public function success($text)
    {
        $style = new OutputFormatterStyle('black', 'green');
        $this->output->getFormatter()->setStyle('success', $style);
        $this->output->newline();
        $this->line("<success>[SUCCESS]</> $text");
    }

    /**
     * Creates a better format for confirmation output.
     *
     * @param  string $question
     * @param  boolean $default
     * @return void
     */
    public function confirm($question, $default = false)
    {
        $style = new OutputFormatterStyle('black', 'cyan');
        $this->output->getFormatter()->setStyle('prompt', $style);
        $this->output->newline();
        return parent::confirm("<prompt>[CONFIRM]</> <fg=white>$question</>", $default);
    }

    /**
     * Creates a better format for error output.
     *
     * @param  string $string
     * @param  string $verbosity
     * @return void
     */
    public function error($string, $verbosity = null)
    {
        $this->output->newline();
        parent::error("<error>[ERROR]</> $string");
    }
}
