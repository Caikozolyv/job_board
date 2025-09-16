<?php

declare(strict_types=1);

namespace App\DTO\Form;


use App\Entity\Action;
use App\Entity\Presence;
use App\Entity\Website;
use Doctrine\ORM\EntityManagerInterface;

class DataFormJob implements DataFormInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) { }

    public function getFieldsType(): array
    {
        $presencesFields = $this->createSelectForObject(Presence::class, 'presences');
        $actionsFields = $this->createSelectForObject(Action::class, 'actions');
        $websitesFields = $this->createSelectForObject(Website::class, 'websites');

        return [
            'name' => [
                'type' => 'text',
                'content' => ''
            ],
            'company' => [
                'type' => 'text',
                'content' => ''
            ],
            'url' => [
                'type' => 'url',
                'content' => ''
            ],
            'city' => [
                'type' => 'text',
                'content' => ''
            ],
            'presence' => [
                'type' => 'select',
                'options' => $presencesFields,
                'selected' => []
            ],
            'website' => [
                'type' => 'select',
                'options' => $websitesFields,
                'selected' => []
            ],
            'salary' => [
                'type' => 'text',
                'content' => ''
            ],
            'askedSalary' => [
                'type' => 'text',
                'content' => ''
            ],
            'creationDate' => [
                'type' => 'date',
                'content' => ''
            ],
            'applicationDate' => [
                'type' => 'date',
                'content' => ''
            ],
            'actions' => [
                'type' => 'select',
                'options' => $actionsFields,
                'selected' => [],
                'multiple' => true
            ],
            'status' => [
                'type' => 'integer',
                'content' => ''
            ]

        ];
    }

    private function createSelectForObject(string $entity, string $uri): array
    {
        $objects = $this->em->getRepository($entity)->findAll();

        $selectFields = [];
        foreach ($objects as $object) {
            $selectFields[] = [
                'value' => $object->getId(),
                'text' => $object->getName()
            ];
        }

        return $selectFields;
    }
}