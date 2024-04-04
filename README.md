# Vanguard
Vanguard is a fullstack datatable and search query builder package for Laravel, with a companion Vue frontend composable using the InertiaJS HTTP protocol specification. It provides an elegant API to define tables, columns, actions and refiners for your data.

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
composer require jdw5/vanguard
```

There are no configuration files needed. You may want to publish the stub and command to customise the default table generated.
    
```console
php artisan vendor:publish --tag=vanguard-stubs
```

## Frontend Companion
There is a Vue-Inertia client library available via `npm` that provides a composable around the refiners and table data. View the documentation for that here.

```console
npm install vanguard-client
```

And use as 
```javascript
import { useTable } from 'vanguard-client'

defineProps({
    propName: Object
})

const table = useTable('propName')
```

It comes with in-built query string parsing and data refreshing, bulk selection of table items and generating functions in Javascript for the table.

See the repository for more information, and API documentation.

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

`defineColumns` returns an array where you can specify the columns to display on the frontend. The selected data from the query will be reduced such that only the column properties here are passed to the client. It provides functionality to define the visibility, breakpoints, transform data and more on each column.

`defineRefinements` returns an array of `Filter` and `Sort` child classes to define the refinements available to the user. The documentation contains a complete specification of the provided APIs for this.

`defineActions` returns an array where you can specify the actions to perform on the data. There are three actions types available `PageAction`, `InlineAction` and `BulkAction` to use to separate them.

## Core
We provide a complete API documentation for the classes and traits provided by Vanguard, including the relevant namespacing. Abstract classes are not included in the documentation, but are available in the source code.

### Table
Tables can be generated from the command line using `php artisan make:table`.

#### Defining the Query
The table requires a query to fetch data from. This can be supplied in a number of ways, listed below in order of precendence:
- Passed as the only argument to `Table::make($argument)`
- Overriding the method `protected function defineQuery()` on your table class, returing a `Builder|QueryBuilder` instance
- Setting the property `protected $model` to be the model class to fetch data from
<!-- - Setting the property `protected $table` to be the table name to fetch data from -->
- Setting the property `protected $getModelClassesUsing` to be a function to resolve the model class
- It will attempt to resolve the model class from the table name if none of the above are provided

To modify the query between the query you provide and the data being fetched, you can add another method `protected function beforeFetch(Builder|QueryBuilder $query)` to apply any additional constraints or modifications, most notably for changing sort orders.

You should also define a unique key for each row in the table. This can be done by setting the property `protected $key` to the column name, or by chaining the method `asKey()` onto a column when defining it.

#### Defining columns
Columns define what data is passed to the frontend, and can be used to mutate the data before it is passed among many other things. 
Columns are defined using the `protected function defineColumns(): array` method, which should return an array of `Column` instances. See the Column documentation for the API.

There is an additional property `protected $applyColumns` which can be set to `false` to disable the application of columns to the query. This is useful when you don't want to reduce the data, or are happy with the default columns. This, by default, is set to true.

#### Defining refiners
Vanguard provides a macro on both the `Query\Builder` and `Eloquent\Builder`'s to apply the `Refinement` class to a given query. This is used in the pipeline for generating the table, allowing you to fluently define refinements for the table.

To add refinements to your table, you can define the `protected function defineRefinements(): array` method, which should return an array of `Refinement` instances. See the Refinement documentation for the API.

#### Defining pagination and meta
Vanguard provides a nearly identical API to the default fetching methods provided by Laravel's query builder. You can define the fetching method when making the table, defining the attribute or defining the method. The supported methods are: `get`, `paginate`, `cursorPaginate` and `dynamicPaginate`. In order of precendence:
- Setting the method when making `Table::make()->paginate()`
- Setting `protected $paginateType` to `get`, `paginate`, `cursor` or `dynamic`
- Overriding the method `protected function paginate(): int|array` to return the number of records per page

The chaining method provides an identical API to Laravel's in-built paginate mechanisms. If you don't do this at the make level, you can override the relevant attributes on the table. These are `protected $pageName`, `protected $page`, `protected $columns`, and then the `protected $paginateType`.

There is a `dynamicPaginate` option available. This allows for users to change the number of records per page. This is done safely, and they cannot arbitrarily change the number of records per page - it must be one of the provided options. To enable this, you must return an array of integers from the `protected function definePaginate()` method, where each integer is a valid number of records per page. Alternatively, you can manually set the `perPage` attribute as an array and change the `paginateType` to `dynamic` - but this is not recommended.

The default number of records is set to 10, but can be overriden by changing the attribute `protected $defaultPerPage`. It is recommended that the array provided as page number options contains the default number of records you set. You can also override the query parameter term by changing the attribute `protected $showKey`. By default, the term is `show`.


#### Defining actions
Actions are defined using the `protected defineActions(): array` method, which should return an array of `Action` instances. See the Action documentation for the API. These are then grouped by their type for access on your frontend.

#### Defining preferences
Preferences are a way to dynamically change the data that is sent to the frontend based on a user changing the selection. This behaviour is not enabled by default. It will add a `paging_options` property to the table data object, containing columns to be used as preferences.

To enable preferences, you must define the the key to be used for the preferences in the search query. This can be done by setting the property `protected $preferencesKey` to the name of your choice (`cols` is a common name), or by overriding the method `protected definePreferenceKey(): string` to return the key.

The columns you have defined can then have the `preference()` method chained onto them to enable them as preferences. If you have columns applied, this will then prevent any data not in those preferences from being sent to the frontend. However, the query must select the necessary columns for all possible preferences - as the preferencing is done at the server level, not database.

Additionally, Vanguard provides functionality to store a user's preferences for a given table through a cookie. To enable this, the cookie name must be defined in the table class. This can be done by setting the property `protected $preferenceCookie` to the name of your choice, or by overriding the method `protected definePreferenceCookie(): string` to return the name. It is critical that this key is unique amongst all your tables, and cookies, to prevent conflicts.

## Component Documentation

### Columns
Columns are used exclusively in the `Table` class to define the data that is passed to the frontend. They can be used to mutate the data before it is passed, define the visibility, breakpoints and more. Columns are generated using `Column::make($name)`. The `$name` parameter should be the name of the column in the database if you are applying columns. The remaining properties are autogenerated, or require chaining using the following methods:
| Method | Description |
| --- | --- |
| `asKey()` | Sets the column as the key for the table, if the key is not defined. Should only be used on one column to prevent conflicts. |
| `name(string $name)` | Sets the name of the column to be displayed on the frontend. This should never be used; it is called when in the constructor. |
| `type(string $type)` | Sets the type of the column; it is currently not in use. |
| `label(string $label)` | Sets the label of the column to be displayed on the frontend. A default is created when the column is made based on the column name, but is often overriden |
| `metadata(array $any)` | Sets any metadata of the column to be passed to the frontend. This can be used to store any additional information about the column. |
| `fallback(mixed $fallback)` | Sets the fallback value of the column to be displayed when the column is empty. |
| `transform(callable $transform)` | Sets a transformation function to be applied to the column data before it is passed to the frontend. |
| `sort(?string $name, ?string $property)` | Sets the column to be sortable using a toggle sort. Both parameters can be ignored if the desired query term, and database column are the same as the column name. |
| `exclude(bool\|Closure $condition)` | Sets the column to be excluded from the data passed to the frontend if the condition evaluates to true. |
| `include(bool\|Closure $condition)` | Sets the column to be included in the data passed to the frontend if the condition evaluates to true. |
| `hide()` | Sets the column to be hidden, these columns are still retrieved but are noted as not being table headers. |
| `show()` | Sets the column to be shown, these columns are retrieved and are table headers. |
| `srOnly()` | Sets the column to be hidden from view, but still accessible to screen readers. |
| `breakpoint(string $breakpoint)` | Sets the breakpoint of the column to be displayed on the frontend. This can be `xs`, `sm`, `md`, `lg`, `xl` or `xxl`. |
| `xs()` | Sets the column to be displayed on the `xs` breakpoint. |
| `sm()` | Sets the column to be displayed on the `sm` breakpoint. |
| `md()` | Sets the column to be displayed on the `md` breakpoint. |
| `lg()` | Sets the column to be displayed on the `lg` breakpoint. |
| `xl()` | Sets the column to be displayed on the `xl` breakpoint. |
| `xxl()` | Sets the column to be displayed on the `xxl` breakpoint. |
| `preference(bool $default)` | Sets the column to be a preference column, which the user can control. Default columns are shown if the user has no preferences. |


### Actions
Classes which extend `BaseAction` are used to define simple methods users can perform on a table. To generate an action, `InlineAction::make(string $name)`. The name is used as the key for the action in it's group or table. The remaining properties are autogenerated, or require chaining using the following methods:
| Method | Description |
| --- | --- |
| `name(string $name)` | Sets the name of the action to be displayed on the frontend. This is executed in the constructor. |
| `label(string $label)` | Sets the label of the action to be displayed on the frontend, can be generated from construction |
| `metadata(array $any)` | Sets any metadata of the action to be passed to the frontend. This can be used to store any additional information about the action. |
| `exclude(bool\|Closure $condition)` | Sets the action to be excluded from the data passed to the frontend if the condition evaluates to true. |
| `include(bool\|Closure $condition)` | Sets the action to be included in the data passed to the frontend if the condition evaluates to true. |

### Inline Action
Inline action has an additional property for default, often used to denote the default action to be performed when a row is clicked.

| Method | Description |
| --- | --- |
| `default()` | Denotes an inline action as the default behaviour |

#### Page Action
Currently, only page actions have access to the endpoint API as these pages do not generally require data to be passed and can be built with only server data.

| Method | Description |
| --- | --- |
| `endpoint(string $endpoint)` | Sets the endpoint of the action to be executed when the action is performed. This should be a route. |
| `method(string $method)` | Sets the method of the action to be executed when the action is performed. This should be a valid HTTP method, it defaults to `post`. |
| `post()` | Sets the method of the action to be executed when the action is performed to `post`. |
| `get()` | Sets the method of the action to be executed when the action is performed to `get`. |
| `put()` | Sets the method of the action to be executed when the action is performed to `put`. |
| `delete()` | Sets the method of the action to be executed when the action is performed to `delete`. |
| `patch()` | Sets the method of the action to be executed when the action is performed to `patch`. |

### Refinements
Refinements are a way to define search parameters, and apply them to a query using a fluent API. The constructor for all refinements is slightly more complex:
```php
Refinement::make(string $property, ?string $name)
```
The property field is required, and denotes the specific database column. The name field is optional, and is used to display the refinement on the frontend. If no name is provided, the property is used as the name. The constructor also generates a label, which fallbacks to the name then property but is often overriden. The remaining API for the refinement is provided below:

| Method | Description |
| --- | --- |
| `property(string $property)` | Sets the property of the refinement to be used in the query. This should not be set. |
| `name(string $name)` | Sets the name of the refinement to be displayed on the frontend. This can be autogenerated, and should be set in the constructor |
| `label(string $label)` | Sets the label of the refinement to be displayed on the frontend. This can be autogenerated, but is often wanted to be overriden. |
| `type(string $type)` | Used to define the type of refinement, allowing you to extend and create many types of refiners. |
| `metadata(array $any)` | Sets any metadata of the refinement to be passed to the frontend. This can be used to store any additional information about the refinement. |
| `exclude(bool\|Closure $condition)` | Sets the refinement to be excluded from the data passed to the frontend if the condition evaluates to true. |
| `include(bool\|Closure $condition)` | Sets the refinement to be included in the data passed to the frontend if the condition evaluates to true. |

There are some specific refiners created for you, which should cover almost every use case in reality. These are formed from bases:

#### Sorts
The `BaseSort` API adds a direction to the refinement to accomodate for the ordering of the data. The standard `Sort` option extends this base sort with no additional parameters.

| Method | Description |
| --- | --- |
| `direction(string $direction)` | Sets the direction of the sort to be used in the query. This can be `asc` or `desc`. |
| `asc()` | Sets the direction of the sort to be `asc`. |
| `desc()` | Sets the direction of the sort to be `desc`. |

The `ToggleSort` API is not available for extension, and so not provided. There are no chaining methods, as it is all handled internally.

#### Filters
Filters provide a way to filter the data based on a specific property. The `BaseFilter` API adds a value to the refinement to accomodate for the filtering of the data.

| Method | Description |
| --- | --- |


### Options