<?php

return [
    'actions' => [
        'move' => [
            'class' => \App\Services\Robot\Actions\MoveAction::class,
        ],
        'pile' => [
            'class' => \App\Services\Robot\Actions\PileAction::class,
        ],
    ],
];
