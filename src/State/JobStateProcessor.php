<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\DTO\JobInput;
use App\Entity\Action;
use App\Entity\Job;
use App\Entity\Presence;
use App\Entity\Website;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;

class JobStateProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface     $persistProcessor,
        private readonly EntityManagerInterface $entityManager,
        private readonly RequestStack $requestStack,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $presenceRepo = $this->entityManager->getRepository(Presence::class);
        $websiteRepo = $this->entityManager->getRepository(Website::class);
        $actionsRepo = $this->entityManager->getRepository(Action::class);

        if ($data instanceof JobInput && $operation instanceof Post) {
            $presence = $presenceRepo->find($data->presence);
            $website = $websiteRepo->find($data->website);
            $actions = $actionsRepo->findBy(['id' => $data->actions]);

            $job = new Job();
            $job
                ->setName($data->name)
                ->setCompany($data->company)
                ->setUrl($data->url)
                ->setCity($data->city)
                ->setPresence($presence)
                ->setWebsite($website)
                ->setSalary($data->salary)
                ->setAskedSalary($data->askedSalary)
                ->setCreationDate($data->creationDate)
                ->setApplicationDate($data->applicationDate)
                ->setStatus(1)
                ->addActions($actions);

            return $this->persistProcessor->process($job, $operation, $uriVariables, $context);
        }

        // for now, when editing a job, flush it and rewrite everything
        if ($data instanceof Job && $operation instanceof Patch) {
            // getting data sent because its different from api platform
            $request = $this->requestStack->getCurrentRequest();
            $inputData = json_decode($request->getContent(), true);

            // getting data for nested relations and dates
            $presenceId = $inputData['tempObj']['presence']['id'];
            $websiteId = $inputData['tempObj']['website']['id'];
            $actionIds = array_map(fn($action) => $action['id'], $inputData['tempObj']['actions']);
            $creationDate = $inputData['tempObj']['creationDate'];
            $applicationDate = $inputData['tempObj']['applicationDate'];

            $presence = $presenceRepo->find($presenceId);
            $website = $websiteRepo->find($websiteId);
            $actions = $actionsRepo->findBy(['id' => $actionIds]);

            // removing nested relations and dates to create dynamic setters
            unset($inputData['tempObj']['presence']);
            unset($inputData['tempObj']['website']);
            unset($inputData['tempObj']['actions']);
            unset($inputData['tempObj']['creationDate']);
            unset($inputData['tempObj']['applicationDate']);

            $data->setPresence($presence);
            $data->setWebsite($website);
            $data->addActions($actions);
            $data->setCreationDate(\DateTime::createFromFormat('d-m-Y', $creationDate));
            $data->setApplicationDate(\DateTime::createFromFormat('d-m-Y', $applicationDate));

            // dynamic setters for the remaining properties
            foreach ($inputData['tempObj'] as $field => $value) {
                $setter = 'set' . ucfirst($field);
                $data->$setter($value);
            }

            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
