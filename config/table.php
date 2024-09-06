<?php

return [
    'action_route' => '/actions',
    /*
    |--------------------------------------------------------------------------
    | Search configuration
    |--------------------------------------------------------------------------
    |
    | This option controls the default search configuration for a table when
    | not overriden on an inidiual table basis. The key is the query string
    | parameter to use for the search term. You can specify to use scout
    | searching for every table, this assumes you have Scout configured.
    */
    'search' => [
        'key' => 'q',
        'scout' => false,
        'columns' => [
            // 'name',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Remember configuration
    |--------------------------------------------------------------------------
    */
    'remember' => [
        'default' => false,
        'duration' => 2592000,
        'toggle_key' => 'cols',
        'cookie' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Sorting configuration
    |--------------------------------------------------------------------------
    */
    'sorting' => [
        'sort_key' => 'sort',
        'order_key' => 'order',
        'default_order' => 'asc', // 'asc' or 'desc'
        // 'signed' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination configuration
    |--------------------------------------------------------------------------
    */
    'pagination' => [
        'default' => 10,
        'name' => 'page',
        'key' => 'show',
    ],

    /*
    |--------------------------------------------------------------------------
    | Chunk strategy
    |--------------------------------------------------------------------------
    */
    'chunk' => [
        'by_id' => true,
        'size' => 500,
        // 'lazy' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Confirmation configuration
    |--------------------------------------------------------------------------
    */
    'confirm' => [
        'title' => 'Confirm',
        'description' => 'Are you sure you want to perform this action?',
        'cancel' => 'Cancel',
        'submit' => 'Confirm',
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallbacks
    |--------------------------------------------------------------------------
    |
    | Control the fallback values for columns when the value is null.
    |
    */
    'fallback' => [
        'default' => null,
        'text' => '—',
        'numeric' => 0,
        'true' => 'Yes',
        'false' => 'No',
    ],
];
