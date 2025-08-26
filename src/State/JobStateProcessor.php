<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\DTO\JobInput;
use App\Entity\Action;
use App\Entity\Job;
use App\Entity\Presence;
use App\Entity\Website;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class JobStateProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface     $persistProcessor,
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof JobInput) {
            $presence = $this->entityManager->getRepository(Presence::class)->find($data->presence);
            $website = $this->entityManager->getRepository(Website::class)->find($data->website);
            $actions = $this->entityManager->getRepository(Action::class)->findBy(['id' => $data->actions_to_take]);

            $job = new Job();
            $job
                ->setName($data->name)
                ->setCompany($data->company)
                ->setUrl($data->url)
                ->setCity($data->city)
                ->setPresence($presence)
                ->setWebsite($website)
                ->setSalary($data->salary)
                ->setAskedSalary($data->asked_salary)
                ->setCreationDate($data->creation_date)
                ->setApplicationDate($data->application_date)
                ->setStatus(1);

            foreach ($actions as $action) {
                $job->addAction($action);
            }

            return $this->persistProcessor->process($job, $operation, $uriVariables, $context);
        }
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
