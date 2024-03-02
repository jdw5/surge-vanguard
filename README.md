# Surge Vanguard
Rapidly develop refiners and table data for Vue + Inertia + Laravel applications.

Inspired by hybridly.

## Table of Contents
- [Installation](#installation)
- [Frontend Companion](#frontend-companion)
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
php artisan vendor:publish --tag=surge-vanguard-stubs
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