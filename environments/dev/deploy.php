<?php
/*
 * This file has been generated automatically.
 * Please change the configuration for correct use deploy.
 */
namespace Deployer;

require 'recipe/common.php';


// Set configurations
// Edit this
set('gitlab_user', '');
set('gitlab_pass', '');

server('beta', 'mdmunir.xyz')
    ->user('<user>')
    ->password(null)
    ->env('deploy_path', '/var/www/html/piknikio/')
    ->stage('beta');


// ****************** //
set('gitlab_user_pass', function() {
    if ($user = get('gitlab_user')) {
        $result = $user;
        if ($pass = get('gitlab_pass')) {
            $result .= ':' . $pass;
        }
        return $result . '@';
    }
    return '';
});

set('repository', 'https://{{gitlab_user_pass}}gitlab.com/psmx/piknikio.git');

set('shared_dirs', [
    'app/runtime',
    'rest/runtime',
]);
// Yii 2 Advanced Project Template shared files
set('shared_files', [
    'app/config/bootstrap-local.php',
    'app/config/console-local.php',
    'app/config/main-local.php',
    'app/config/params-local.php',
    'app/config/web-local.php',
    'rest/config/main-local.php',
]);
/**
 * Initialization
 */
task('deploy:init', function () {
    run('{{bin/php}} {{release_path}}/init --env=Production --overwrite=n');
})->desc('Initialization');
/**
 * Run migrations
 */
task('deploy:run_migrations', function () {
    run('{{bin/php}} {{release_path}}/yii migrate --interactive=0');
})->desc('Run migrations');
/**
 * Main task
 */
task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:init',
    'deploy:run_migrations',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
])->desc('Deploy your project');

after('deploy', 'success');
