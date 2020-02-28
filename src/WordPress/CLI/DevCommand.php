<?php

declare(strict_types=1);

namespace App\WordPress\CLI;

use Roots\WPConfig\Config;

/**
 * Manages the local development environment.
 */
class DevCommand extends \WP_CLI_Command
{
    /**
     * Wait for the WordPress database server to become available.
     *
     * Waits for the WordPress database server to become available, then executes a command.
     *
     * ## OPTIONS
     *
     * [--retry-count=<retry-count>]
     * : Number of times to retry connecting to the database before giving up.
     * ---
     * default: 10
     * ---
     *
     * [--retry-delay=<retry-delay>]
     * : Delay, in seconds, between retrying connection to the database.
     * ---
     * default: 5
     * ---
     *
     * [--command=<command>]
     * : The WP-CLI command to execute once a connection to the database has been established.
     * ---
     * default: dev bootstrap
     * ---
     *
     * @param array $arguments
     * @param array $namedArguments
     *
     * @subcommand wait-for-database
     * @when before_wp_load
     */
    public function waitForDatabase(array $arguments, array $namedArguments): void
    {
        require __DIR__ . '/../../../config/application.php';

        $retryCount = (int)$namedArguments['retry-count'];
        $retryDelay = (int)$namedArguments['retry-delay'];
        $command = (string)$namedArguments['command'];

        $host = explode(':', Config::get('DB_HOST'));
        $port = $host[1] ?? 3306;
        $host = $host[0];

        $mysql = new \mysqli();

        \WP_CLI::line('Connecting to database...');

        $connected = @$mysql->real_connect(
            $host,
            Config::get('DB_USER'),
            Config::get('DB_PASSWORD'),
            Config::get('DB_NAME'),
            $port
        );

        if (!$connected) {
            foreach (range(1, $retryCount) as $attempt) {
                sleep($retryDelay);
                \WP_CLI::line(sprintf('Retry %d', $attempt));
                $connected = @$mysql->real_connect(
                    $host,
                    Config::get('DB_USER'),
                    Config::get('DB_PASSWORD'),
                    Config::get('DB_NAME'),
                    $port
                );

                if ($connected) {
                    break;
                }
            }
        }

        if ($connected) {
            \WP_CLI::success('Connected to the database!');
            \WP_CLI::runcommand($command);
        } else {
            try {
                \WP_CLI::error('Could not connect to the database.', false);
            } catch (\Exception $e) {
                //
            }
        }
    }

    /**
     * Install the WordPress database.
     *
     * Will install the WordPress database for the configured environment.
     *
     * @subcommand install-database
     * @when before_wp_load
     */
    public function installDatabase(): void
    {
        require __DIR__ . '/../../../config/application.php';

        $command = 'core install';
        $namedArguments = [
            '--url' => Config::get('WP_HOME'),
            '--title' => Config::get('WP_SITE_TITLE'),
            '--admin_user' => Config::get('WP_ADMIN_USER'),
            '--admin_password' => Config::get('WP_ADMIN_PASS'),
            '--admin_email' => Config::get('WP_ADMIN_EMAIL'),
            '--skip-email' => null
        ];

        $arguments = [];
        foreach ($namedArguments as $name => $argument) {
            if (is_null($argument)) {
                $arguments[] = $name;
            } else {
                $arguments[] = sprintf('%s=%s', $name, escapeshellarg($argument));
            }
        }

        $fullCommand = sprintf(
            '%s %s',
            $command,
            implode(' ', $arguments)
        );

        \WP_CLI::runcommand($fullCommand);
    }

    /**
     * Bootstraps WordPress.
     *
     * Bootstraps the WordPress database and environment. Nothing beats
     * an automated WordPress installation!
     *
     * @subcommand bootstrap
     * @when before_wp_load
     */
    public function bootstrap(): void
    {
        $commands = [
            'dotenv salts generate',
            'dev install-database',
            'dev bootstrap-late'
        ];

        foreach ($commands as $command) {
            \WP_CLI::runcommand($command);
        }
    }

    /**
     * Finishes bootstrapping WordPress after the initial environment is loaded.
     *
     * NOT INTENDED TO BE CALLED DIRECTLY!
     *
     * @subcommand bootstrap-late
     * @when after_wp_load
     */
    public function bootstrapLate(): void
    {
        $commands = [
//            'theme activate generatepress',
            'rewrite structure /%postname%/ --hard' # Set Permalink settings to "Post name" default
        ];

        foreach ($commands as $command) {
            \WP_CLI::runcommand($command);
        }
    }
}
