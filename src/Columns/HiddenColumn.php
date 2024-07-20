<?php

// namespace Conquest\Table\Columns;

// use Closure;
// use Conquest\Core\Concerns\IsKey;

// class HiddenColumn extends BaseColumn
// {
//     use IsKey;

//     public function setUp(): void
//     {
//         $this->setType('col:hidden');
//     }

//     public function __construct(
//         string|Closure $name, 
//         string|Closure $label = null,
//         mixed $fallback = null,
//         Closure|bool $authorize = null, // Remove from parent
//         Closure $transform = null,
//         string $breakpoint = null,
//         bool $srOnly = false,
//         bool $sortable = false,
//         bool $active = true,
//         array $metadata = null,
//     ) {
//         parent::__construct(
//             name: $name, 
//             label: $label, 
//             hidden: true, 
//             fallback: $fallback,
//             transform: $transform, 
//             breakpoint: $breakpoint, 
//             srOnly: $srOnly, 
//             sortable: $sortable, 
//             metadata: $metadata
//         );
//         $this->setKey(true);
//     }
    
//     public static function make(
//         string|Closure $name, 
//         string|Closure $label = null,
//         bool $hidden = false,
//         Closure $transform = null,
//         string $breakpoint = null,
//         bool $srOnly = false,
//         bool $sortable = false,
//         array $metadata = null,
//     ): static {
//         return resolve(static::class, compact(
//             'name',
//             'label',
//             'hidden',
//             'transform',
//             'breakpoint',
//             'srOnly',
//             'sortable',
//             'metadata',
//         ));
//     }
// }
