<?php

// TODO: Parse config from XML
return $config = [
    'class'         => \JodaYellowBox\Models\Ticket::class, # class of your domain object
    'graph'         => 'default', // Name of the current graph - there can be many of them attached to the same object
    'property_path' => 'state',       // Property path of the object actually holding the state
    'states'        => [
        'open',
        'approved',
        'rejected',
        'reopened'
    ],
    'transitions' => [
        'approve' => [
            'from' => ['open', 'reopened'],
            'to'   => 'approved'
        ],
        'reject' => [
            'from' => ['open', 'reopened'],
            'to'   => 'rejected'
        ],
        'reopen' => [
            'from' => ['rejected'],
            'to'   => 'reopened'
        ]
    ],
    'callbacks' => [
        'after' => [
            'on-approve' => [
                'on' => 'approve',                     # call the callback on a specific transition
                'do' => ['@joda_yellow_box.sql_state_logger', 'log'],  # will call the method of this Symfony service
                'args' => ['object']
            ],
            'on-reject' => [
                'on' => 'reject',                     # call the callback on a specific transition
                'do' => ['@joda_yellow_box.sql_state_logger', 'log'],  # will call the method of this Symfony service
                'args' => ['object']
            ],
            'on-reopen' => [
                'on' => 'reopen',                     # call the callback on a specific transition
                'do' => ['@joda_yellow_box.sql_state_logger', 'log'],  # will call the method of this Symfony service
                'args' => ['object']
            ]
        ]
    ]
];
