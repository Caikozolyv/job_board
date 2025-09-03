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
            $actions = $actionsRepo->findBy(['id' => $data->actions_to_take]);

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

        if ($data instanceof Job && $operation instanceof Patch) {
            $request = $this->requestStack->getCurrentRequest();
            $inputData = json_decode($request->getContent(), true);

            $presenceId = $inputData['tempObj']['presence']['id'];
            $websiteId = $inputData['tempObj']['website']['id'];

            $presence = $presenceRepo->find($presenceId);
            $website = $websiteRepo->find($websiteId);

            unset($inputData['tempObj']['presence']);
            unset($inputData['tempObj']['website']);

            $data->setPresence($presence);
            $data->setWebsite($website);

            foreach ($inputData['tempObj'] as $field => $value) {
                $setter = 'set' . ucfirst($field);
                $data->$setter($value);
            }

            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
