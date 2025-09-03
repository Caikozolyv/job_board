<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\Job;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @method array getSupportedTypes(?string $format)
 */
class JobNormalizer implements NormalizerInterface
{
    public function __construct(private ObjectNormalizer $normalizer)
    {}

    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        // handle relations
        $data['website'] = $object->getWebsite()->getName();
        $data['presence'] = $object->getPresence()->getName();

        return $data;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if (!$data instanceof Job) {
            return false;
        }

        // Vérifier l'opération - UNIQUEMENT pour GetCollection
        $operation = $context['operation'] ?? null;

        if ($operation) {
            return false;
        }

        return true;
    }
}