<?php

namespace App\ErrorRenderer;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class JsonErrorRenderer implements NormalizerInterface
{
    public function normalize(mixed $object, ?string $format = null, array $context = [])
    {
        if (isset($context['debug']) && $context['debug']) {
            $message = $object->getMessage();
        } else {
            $message = $object->getStatusText();
        }

        return [
            'success' => false,
            'message' => $message,
        ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null)
    {
        if ($data instanceof FlattenException) {
            return true;
        }

        return false;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            FlattenException::class => false,
        ];
    }
}