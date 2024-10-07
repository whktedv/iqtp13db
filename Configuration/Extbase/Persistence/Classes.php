<?php
declare(strict_types = 1);
return [
    //Mapping
    \Ud\Iqtp13db\Domain\Model\Berater::class => [
        'tableName' => 'fe_users',
        'properties' => [
            'username' => [
                'fieldName' => 'username'
            ],
            'password' => [
                'fieldName' => 'password'
            ],
            'usergroup' => [
                'fieldName' => 'usergroup'
            ],
            'firstName' => [
                'fieldName' => 'firstName'
            ],
            'lastName' => [
                'fieldName' => 'lastName'
            ],
            'email' => [
                'fieldName' => 'email'
            ],
            'company' => [
                'fieldName' => 'company'
            ],
            'lastlogin' => [
                'fieldName' => 'lastlogin'
            ],
        ],
    ],
    \Ud\Iqtp13db\Domain\Model\UserGroup::class => [
        'tableName' => 'fe_groups',
        'properties' => [
            'title' => [
                'fieldName' => 'title'
            ],
        ],
    ],
];

