# Adapter to use Vars in Laravel

[Vars](https://github.com/m1/Vars#usage) is a simple to use and easily extendable configuration file loader for PHP
with built-in support for env, INI, JSON, PHP, Toml, XML and YAML config file types.

## Installation

#### Library

```bash
git clone https://github.com/ntd1712/config.git
```

#### Composer

This can be installed with [Composer](https://getcomposer.org/doc/00-intro.md)

Define the following requirement in your `composer.json` file.

```json
{
    "require": {
        "chaos/config": "*"
    },

    "repositories": [
      {
        "type": "vcs",
        "url": "https://github.com/ntd1712/config"
      },
      {
        "type": "package",
        "package": {
          "name": "m1/vars",
          "version": "1.1.3-dev",
          "dist": {
            "url": "https://github.com/ntd1712/Vars/archive/1.1.3.zip",
            "type": "zip"
          },
          "autoload": {
            "psr-4": {
              "M1\\Vars\\": "src/"
            }
          }
        }
      }
    ]
}
```

#### Usage

```php
<?php // For example, in Laravel

use Chaos\Support\Config\VarsConfigAdapter;
use M1\Vars\Vars;

$basePath = $container->basePath();
$vars = new VarsConfigAdapter(
    new Vars(
        array_merge(
            glob($basePath . '/modules/core/*/config/config.yml', GLOB_NOSORT),
            glob($basePath . '/modules/app/*/config.yml', GLOB_NOSORT),
            [$basePath . '/modules/config.yml']
        ),
        [
            'cache' => $container->isProduction(),
            'cache_path' => $basePath . '/storage/framework/cache', #/vars
            'loaders' => ['yaml'],
            'merge_globals' => false,
            'replacements' => [
                'base_path' => $basePath
            ]
        ]
    )
);
$container['vars'] = $vars;

$appKey = $vars->get('app.key');
