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
composer require jdw5/vanguard
```

There are no configuration files needed. You may want to publish the stub and command to customise the stub and add options to the command.
    
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

const props = defineProps({
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

## Documentation
We provide a complete API documentation for the classes and traits provided by Vanguard, including the relevant namespacing. Abstract classes are not included in the documentation, but are available in the source code.
### Classes
#### `Jdw5\Vanguard\Table\Table`
The table is a concrete Tables can be generated from the command line using `php artisan make:table`.
##### Traits
| Documentation | Source |
|-----------------|-----------------|
| HasColumns | Source |
| HasActions | Source |
| HasId | Source |
| HasModel | Source |
| HasPagination | Source |
| HasRecords | Source |
| HasRefinements | Source |
| HasKey | Source |
| HasDynamics | Source |
| HasDynamicPagination | Source |

##### Attributes
| Attribute | Type | Description |
|-----------------|-----------------|-----------------|
| model | string | The model class to use for the table. |
| table | string | The table name to use for the table. |


##### Methods
#### `Jdw5\Vanguard\Table\Column`

#### Refinement
##### Sort

#### ToggleSort

##### Filter

##### SelectFilter

##### DateFilter


##### QueryFilter

#### Option

## Traits