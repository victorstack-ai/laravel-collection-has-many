[![CI](https://github.com/victorstack-ai/laravel-collection-has-many/actions/workflows/ci.yml/badge.svg)](https://github.com/victorstack-ai/laravel-collection-has-many/actions/workflows/ci.yml)
[![PHP Version](https://img.shields.io/badge/php-8.1%20|%208.2%20|%208.3%20|%208.4-blue)](https://www.php.net/)
[![License: MIT](https://img.shields.io/badge/license-MIT-green)](LICENSE)

# Laravel Collection hasMany() Method

This library implements a `hasMany()` macro for Laravel Collections, allowing you to easily associate related collections of data, similar to Eloquent eager loading but for plain arrays and objects.

## Installation

```bash
composer require victorstack/laravel-collection-has-many
```

## Usage

First, register the macro:

```php
use Victorstack\LaravelCollectionHasMany\CollectionHasMany;

CollectionHasMany::register();
```

Then use it on any collection:

```php
$users = collect([
    ['id' => 1, 'name' => 'Alice'],
    ['id' => 2, 'name' => 'Bob'],
]);

$posts = collect([
    ['id' => 101, 'user_id' => 1, 'title' => 'Post A'],
    ['id' => 102, 'user_id' => 1, 'title' => 'Post B'],
    ['id' => 103, 'user_id' => 2, 'title' => 'Post C'],
]);

$users = $users->hasMany($posts, 'user_id', 'id', 'posts');

/*
$users is now:
[
    ['id' => 1, 'name' => 'Alice', 'posts' => [...Post A, Post B]],
    ['id' => 2, 'name' => 'Bob', 'posts' => [...Post C]],
]
*/
```

## Features

- Works with both arrays and objects.
- O(n+m) performance using grouping.
- Customizable local key, foreign key, and relation name.
- Automatically converts arrays to collections if needed.
