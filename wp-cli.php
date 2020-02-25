<?php

require __DIR__ . '/vendor/autoload.php';

WP_CLI::add_command('dev', \App\WordPress\CLI\DevCommand::class);
