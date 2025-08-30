<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;

class DefaultStateProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface $persistProcessor,
        private readonly RequestStack $requestStack,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        // Necessary for edition as data sent by API platform is different from the one in the request
        if ($operation instanceof Patch) {
            $request = $this->requestStack->getCurrentRequest();
            $inputData = json_decode($request->getContent(), true);

            error_log('Data from API Platform: ' . json_encode($data));
            error_log('REAL input data: ' . json_encode($inputData));

            // same as $data
//            $objectToUpdate = $this->entityManager->getRepository(get_class($data))->find($data->getId());

            foreach ($inputData['tempObj'] as $field => $value) {
                $setter = 'set' . ucfirst($field);
                $data->$setter($value);
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}