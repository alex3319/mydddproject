<?php

declare(strict_types=1);

namespace App\Factory;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class JsonResponseFactory
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function create(?object $data, int $status = 200, array $headers = []): JsonResponse
    {
        $entityAsArray = $this->serializer->normalize($data, null);

        $response = new JsonResponse($entityAsArray);
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        return $response;
    }
}
