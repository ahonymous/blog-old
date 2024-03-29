<?php

require_once __DIR__.'/base_script.php';

build_bootstrap();

show_run("Changing permissions", "chmod -R 777 app/cache app/logs web/");
show_run("database:drop", "app/console doctrine:database:drop --force");
show_run("database:create", "app/console doctrine:database:create");
//show_run("generate:entities", "app/console doctrine:generate:entities Ahonymous");
show_run("schema:update", "app/console doctrine:schema:update --force");

show_run("Destroying cache dir manually", "rm -rf app/cache/*");   // ***********************

show_run("Creating directories for app kernel", "mkdir -p app/cache/dev app/logs");   // *******************

show_run("Warming up dev cache", "php app/console --env=dev cache:clear");
//show_run("Warming up test cache", "php app/console --env=test cache:clear");

show_run("Changing permissions", "chmod -R 777 app/cache app/logs web/");
show_run("fixtures:load", "app/console doctrine:fixtures:load --no-interaction");

show_run("assets:install", "app/console assets:install --symlink");
show_run("assets:dump", "app/console assetic:dump");

show_run("Warming up dev cache", "php app/console --env=dev cache:clear --no-warmup");
show_run("Changing permissions", "chmod -R 777 app/cache app/logs web/");   // ************************

exit(0);
