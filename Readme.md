# Slimbus  
[Slim Framework](https://www.slimframework.com/) based Statbus!

## Caveats
1. This application is still under heavy development. There are hardcoded references to tgstation/tgstation that cannot be easily overridden without forking this repo.
2. Support and help for downstream installations is **NOT** being offered at this time. This will change as this project matures. PRs and bug reports are  always welcome.

## Setup
1. [Configure your webserver for Slim](https://www.slimframework.com/docs/v3/start/web-servers.html). 
2. Clone this repo into the document root you specified in step 1.
3. Run  `composer update` and `composer dump-autoload -o`.
4. Copy `.env.example` to `.env` and adjust your settings accordingly.
5. Copy `src/conf/example-servers.json` to `src/conf/servers.json` and customize your settings accordingly.
6. Copy `src/conf/example-ranks.json` to `src/conf/ranks.json` and customize your settings accordingly. Icons are from [FontAwesome](https://fontawesome.com/icons?d=gallery&m=free). 

## Updating
1. Run `git pull`.
2. Run  `composer update` and `composer dump-autoload -o`.
3. (Maybe) Remove the twig cache at `tmp/twig`.
