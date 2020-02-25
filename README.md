# Metisimo
An example WordPress site to showcase how Cascade Media builds sites.

## Prerequisites
Before starting, make sure the following software is installed.

For best results, make sure you're running the latest versions.

- [Node.js](https://nodejs.org/en/)
- [Composer](https://getcomposer.org/)
- [Yarn](https://yarnpkg.com/)

## Getting Started
Simply run `composer setup` to install all dependencies and bootstrap the development environment.

Once done, you should be able to visit `http://localhost:8081` and start working!

## Useful Commands
### Repository management
#### `composer clean`
Deletes all installed packages and distributable directories.

This will effectively reset the repository to a blank slate. Typically only used as a last resort.

#### `composer setup`
Installs all packages necessary to run the application. Once done it will build CSS/JS assets and then start the
development environment.

### Environment management
#### `composer dev:bootstrap`
Installs the WordPress database into the running development environment and activates the default theme.

#### `composer dev:destroy`
Shuts down the development environment and deletes the WordPress database.

#### `composer dev:down`
Shuts down the development environment without deleting the WordPress database.

#### `composer dev:logs`
Streams logs from the containers running the development environment. Useful for tracking database, PHP, or general
website errors.

#### `composer dev:shell`
Starts an interactive command shell inside the development environment. Allows debugging using the same
tools that is running the application.

#### `composer dev:status`
Displays the status of the development environment components.

#### `composer dev:up`
Starts up the development environment then bootstraps it using `composer dev:bootstrap`.

### Asset generation
#### `yarn build`
Builds CSS/JS assets for development usage.

#### `yarn build:production`
Builds CSS/JS asset suitable for production usage, combined and minified.

#### `yarn start`
Builds CSS/JS assets, then starts a BrowserSync watcher to allow for local development with live reloading.
