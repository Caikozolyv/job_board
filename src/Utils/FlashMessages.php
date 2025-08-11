<?php

namespace App\Utils;

class FlashMessages
{
    private const ACTION_TYPE = [
        0 => 'created',
        1 => 'edited',
        2 => 'deleted',
    ];

    /**
     * Display a success flash message for an entity and an action
     *
     * @param string $entity
     * @param int $actionType 0 => created, 1 => edited, 2 => deleted
     *
     * @return void
     */
    public static function displayActionMessage(string $entity, int $actionType): void
    {
        $action = self::ACTION_TYPE[$actionType] ?? null;

        if ($action) {
            $successMessage = sprintf('Your %s has been %s successfully!', $entity, $action);

            flash()
                ->option('position', 'top-left')
                ->option('timeout', 3000)
                ->option('direction', 'bottom')
                ->addSuccess($successMessage);
        }
    }
}