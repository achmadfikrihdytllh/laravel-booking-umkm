<?php

return [
    'paths' => [resource_path('views')],

    // Tanpa realpath(): kalau folder belum ada, Laravel membuatnya sendiri, bukan error "valid cache path".
    'compiled' => env('VIEW_COMPILED_PATH', storage_path('framework/views')),
];
