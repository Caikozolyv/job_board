<?php

declare(strict_types=1);

namespace App\DTO\Table;

use App\Entity\Presence;

class DataTablePresence implements DataTableInterface
{
    public function getNecessaryValues(
        /** @var Presence[] $presences */
        array $presences
    ): array {
        $presencesValues = [];
        foreach ($presences as $presence) {
            $presencesValues[] =
                [
                    'id' => $presence->getId(),
                    'name' => $presence->getName()
                ];
        }
        return $presencesValues;
    }

    public function getFieldsType(): array
    {
        return [
            'name' => [
                'type' => 'text',
                'content' => ''
            ]
        ];
    }
}