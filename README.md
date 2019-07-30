# ACF Gutenberg

ACF Gutenberg is a plugin that let you create WordPress Gutenberg Blocks very easily using Advanced Custom Fields PHP approach.

## Features

Create custom blocks inside your theme
  - with the help of amazon [ACF Builder Package] (https://github.com/StoutLogic/acf-builder)
  - using blade templating engine
  - and having classes support which help isolating views from logic.

## Requirements

- Advanced Custom Fields PRO >= 5.8 (Currently on Beta)
- WordPress >= 5.0
- Use (Bedrock)[https://github.com/roots/bedrock] Boilerplate

## Installation

Download the plugin

### Via Command line
Install via composer
```sh
# bedrock root folder
$ composer require 40q/acf-gutenberg
```

Activate the plugin via (wp-cli)[http://wp-cli.org/commands/plugin/activate/]. Please keep in mind you have to have ACF PRO 5.8+ installed and activated in order to move forward.
```sh
wp plugin activate acf-gutenberg
```

Go to the plugin's root folder and run
```sh
block init
```

This will create a bunch of folders on your active's theme directory.

## Initial Settings


## Block Creation
From your theme folder run `block create block-name` in order to create your first block.

```sh
block create sample-block
```


## Development

Contributions are welcome from everyone.

## CLI

use `php block` to CLI commands.

Commands list:
  - php block init
  - php block create {the-block-name} --target=plugin
  - php block template:list
  - php block import {the-block-name} {prefix} --target=plugin
  - php block list
  - php block clone
  - php block clean
