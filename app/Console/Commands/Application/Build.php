<?php

namespace App\Console\Commands\Application;

use App\Console\BaseCommand;
use Carbon\Carbon;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Storage;
use ZipArchive;

class Build extends BaseCommand
{

    protected $signature = 'app:build {env=dev} {--yes} {--package}';
    protected $description = 'Build application, update version and create an archive file ready to be deployed on a server.';

    // Files and folders to be included into the package file.
    protected $include = [
        '^app/*',
        '^bootstrap/*',
        '^config/*',
        '^database/*',
        '^public/*',
        '^resources/*',
        '^routes/*',
        '^storage/*',
        '^vendor/*',
        '^artisan',
        '^composer.json',
        '^composer.lock',
        '^server.php',
    ];

    // Files and folders to be excluded from the package file.
    protected $exclude = [
        '^storage/framework/cache/(?!(.gitignore))',
        '^storage/framework/sessions/(?!(.gitignore))',
        '^storage/framework/testing/(?!(.gitignore))',
        '^storage/framework/views/(?!(.gitignore))',
        '^storage/clockwork/*',
        '^storage/logs/*',
    ];

    // Current git branch.
    protected $gitCurrentBranch;

    // Last commit (hash) from current branch.
    protected $gitCommitHash;

    // Last commit timestamp.
    protected $gitCommitTimestamp;

    // Latest version tag.
    protected $gitVersionTag;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Validate environment argument.
        if ( ! in_array($this->argument('env'), ['dev', 'prod'])) {
            $this->error('Unsupported build option: ' . $this->argument('env'));
        }

        // Fetch and output build information from Git repository.
        $this->fetchGitInfo();
        $updated = Carbon::parse($this->gitCommitTimestamp)->diffForHumans();
        $this->info("Branch: {$this->gitCurrentBranch}");
        $this->info("Commit: {$this->gitCommitHash} (updated $updated)");
        $this->info("Version: {$this->gitVersionTag}");

        // Confirm if we want to create a new version file to be compiled during JS compiling operation.
        if ($this->option('yes') || $this->confirm('Create new version file?')) {
            $this->createVersionFile();
        }

        // Confirm if we want to install npm packages.
        if ($this->option('yes') || $this->confirm('Install npm packages?')) {
            $this->newline('Installing npm packages...');
            exec('npm install');
        }

        // Confirm if we want to install Composer packages.
        if ($this->option('yes') || $this->confirm('Install Composer packages?')) {
            $this->newline('Installing Composer packages...');
            exec('composer install');
        }

        // Bundle javascript files.
        if ($this->option('yes') || $this->confirm('Bundle javascript files?')) {
            $this->newline('Bundling application javascript files...');
            exec('npm run ' . $this->argument('env'));
        }

        // Create an archive file from application filesystem.
        if ($this->option('package')) {
            $this->package();
        }

        $this->success('Application was built successfully.');
    }

    /**
     * Fetch build information from Git repository.
     *
     * @return void
     */
    protected function fetchGitInfo()
    {
        $this->info('Fetching info from Git repository...');
        $this->gitCurrentBranch = exec('git rev-parse --abbrev-ref HEAD');
        $this->gitCommitHash = exec('git rev-parse --short HEAD');
        $this->gitCommitTimestamp = exec("git show -s --format=%ci {$this->gitCommitHash}");
        $this->gitVersionTag = exec('git describe --abbrev=0 --tags');
    }

    /**
     * Create build version file in app js directory
     * from stub version file.
     *
     * @return void
     */
    protected function createVersionFile()
    {
        $this->newline('Creating version file...');

        $replace = [
            '@version' => $this->gitVersionTag,
            '@build'   => $this->gitCommitHash,
            '@date'    => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        Storage::disk('js')->put('version.js',
            str_replace(array_keys($replace), array_values($replace), Storage::get('stubs/version.stub'))
        );
    }

    /**
     * Helper function which loops through an array of patterns
     * and check if string match any of them.
     *
     * @param  array $patterns
     * @param  string $string
     * @return boolean
     */
    protected function matchPatterns($patterns, $string)
    {
        foreach ($patterns as $pattern) {
            if (preg_match('/' . addcslashes($pattern, '/') . '/', $string) > 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Validate if a file should be added to the archive file.
     *
     * @param  string $filePath
     * @return boolean
     */
    protected function validateFile($filePath)
    {
        return $this->matchPatterns($this->include, $filePath) && ! $this->matchPatterns($this->exclude, $filePath);
    }

    /**
     * Create an archive file from application filesystem
     * ready to be deployed on a server.
     *
     * @return void
     */
    protected function package()
    {
        $this->newline('Creating archive file...');
        $rootPath = base_path();
        $archiveFile = str_replace('Laravel', '', $rootPath) . 'laravel.zip';
        $archive = new ZipArchive();

        if ($archive->open($archiveFile, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);
            foreach ($files as $name => $file) {
                $filePath = $file->getRealPath();
                $baseFilePath = str_replace("$rootPath\\", '', $filePath);
                $baseFilePath = str_replace('\\', '/', $baseFilePath);

                // Validate if file should be added to the package.
                if ( ! is_dir($filePath) && $this->validateFile($baseFilePath)) {
                    // Add file to the archive while replacing backslash for Linux compatibility.
                    $archive->addFile($filePath, $baseFilePath);
                }
            }
        }

        if ($archive->close()) {
            $this->info("Archive file created: $archiveFile");
        }
    }
}
