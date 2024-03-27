# Vanguard
Vanguard is a fullstack datatable and search query builder package for Laravel, with a companion Vue frontend composable using the InertiaJS HTTP protocol specification. It provides an elegant API to define tables, columns, actions and refiners for your data.

Inspired by hybridly.

## Table of Contents
- [Installation](#installation)
- [Frontend Companion](#frontend-companion)
- [Anatomy](#anatomy)
- [Documentation](#documentation)
    - [Tables](#tables)
        - [Creating](#creating)
        - [Defining](#defining)
        - [Columns](#columns)
        - [Actions](#actions)
        - [Refiners](#refiners)
    - [Refiners](#refiners)
        - [Sorting](#sorting)
        - [Filtering](#filtering)

## Installation
Install the package via composer.

```console
composer require surge-vanguard
```

There are no configuration files needed. You may want to publish the stub and command to customise the stub and add options to the command.
    
```console
php artisan vendor:publish --tag=vanguard-stubs
```

## Frontend Companion
There is a Vue-Inertia client library available via `npm` that provides a composable around the refiners and table data. View the documentation for that here.

```console
npm install surge-vanguard
```

And use as 
```javascript
import { useTable } from 'surge-vanguard'

const table = useTable('propname')
```
## Anatomy
The core functionality is the ability to define your tables and perform filtering automatically. The refinements and actions can also be used separately, without the table, but the anatomy will focus on the `Table` class.

When using the `make:table` command, the following boilerplate will be generated:
```php

```

You must define the `model`, `table`  attributes or complete the `defineQuery` method to tell the table what data to fetch. You can also exclude this, and pass in a `Builder` instance to the `UserTable::make(Model::query())` method.

The table is expected a unique column, or identifier for each element. This is particularly useful for generating modals: the `based/momentum-modal` is recommended for providing modal endpoints. The `key` can be defined as an attribute on the model, or you can chain `asKey()` onto a column when defining.

The table will default to performing a `get()` when retrieving records if not collection method is provided. These can be provided when creating the class as such:
```php
$table = UserTable::make()->paginate(10);
```

Or, the recommended method is to complete the `public function paginate()` method. This can return either a single integer or an array of integers. If an array is provided, you have opted in for dynamic pagination and a `show` property will be generated on the table to the frontend. This allows for users to change the number of records per page.


## Documentation
### Tables
#### Creating

#### Defining

#### Columns

#### Actions

#### Refiners

### Refiners
#### Sorting

#### Filtering