<?php
// config/menu.php

return [
    'admin' => [
        [
            'route' => 'dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'label' => 'Dashboard',
        ],
        [
            'route' => 'employee-list',
            'icon' => 'fas fa-folder',
            'label' => 'Employee',
            'subroutes' => ['employee-add', 'employee-edit'],
        ],
        [
            'route' => 'store-list',
            'icon' => 'fas fa-folder',
            'label' => 'Store',
            'subroutes' => ['store-add', 'store-edit'],
        ],
        [
            'route' => 'customer-list',
            'icon' => 'fas fa-folder',
            'label' => 'Customer',
            'subroutes' => ['customer-add', 'customer-edit'],
        ],
        [
            'route' => 'supplier-list',
            'icon' => 'fas fa-folder',
            'label' => 'Supplier',
            'subroutes' => ['supplier-add', 'supplier-edit'],
        ],
        [
            'route' => 'category-list',
            'icon' => 'fas fa-folder',
            'label' => 'Categories',
            'subroutes' => ['category-add', 'category-edit'],
        ],
        [
            'route' => 'brand-list',
            'icon' => 'fas fa-folder',
            'label' => 'Brand',
            'subroutes' => ['brand-add', 'brand-edit'],
        ],
        [
            'route' => 'rack-list',
            'icon' => 'fas fa-folder',
            'label' => 'Racks',
            'subroutes' => ['rack-add', 'rack-edit'],
        ],
        [
            'route' => 'card-list',
            'icon' => 'fas fa-folder',
            'label' => 'Card',
            'subroutes' => ['card-add', 'card-edit'],
        ],
        [
            'route' => 'account-list',
            'icon' => 'fas fa-folder',
            'label' => 'Account',
            'subroutes' => ['account-add', 'account-edit'],
        ],
        // Add more admin menu items as needed
    ],
    'manager' => [
        [
            'route' => 'dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'label' => 'Dashboard',
        ],
        [
            'route' => 'customer-list',
            'icon' => 'fas fa-folder',
            'label' => 'Customer',
            'subroutes' => ['customer-add', 'customer-edit'],
        ],
        // Add more manager menu items as needed
    ],
    'employee' => [
        [
            'route' => 'dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'label' => 'Dashboard',
        ],
        // Add employee-specific menu items as needed
    ],
];
