<?php

declare(strict_types=1);

namespace App\Controller\Main;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BaseCQRSController extends AbstractController
{
    protected const OK = 200;
    protected const RESOURCE_CREATED = 201;
    protected const BAD_REQUEST = 400;
    protected const UNAUTHORIZED = 401;
    protected const PAYMENTREQUIRED = 402;
    protected const FORBIDDEN = 403;
    protected const NOTFOUND = 404;
    protected const TYPE_CREATE = 1;
    protected const TYPE_UPDATE = 2;
    protected const TYPE_DELETE = 3;
    protected const TYPE_INDEX = 4;
    protected const TYPE_VIEW_SLUG = 5;
    protected const TYPE_VIEW_UUID = 6;
    protected const UNKNOWN_REQUEST = 'Неизвестный запрос';

    private ValidatorInterface $validator;
    private MessageBusInterface $bus;
    private SerializerInterface $serializer;

    // jwt
    private TokenStorageInterface $tokenStorageInterface;
    private JWTTokenManagerInterface $jwtManager;
    private $roles = array();

    public function __construct(
        ValidatorInterface $validator,
        MessageBusInterface $bus,
        SerializerInterface $serializer,
        TokenStorageInterface $tokenStorageInterface,
        JWTTokenManagerInterface $jwtManager
    ) {
        $this->validator = $validator;
        $this->bus = $bus;
        $this->serializer = $serializer;

        // jwt payload
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
        if ($this->tokenStorageInterface->getToken()){
            $decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
            if (array_key_exists('roles', $decodedJwtToken)){
                $this->setRoles($decodedJwtToken['roles']);
            }
        }
    }

    // Проверка прав. Нужно ли?
    // $this->denyAccessNotAdministrator();

    // $_GET query parameters
    // $fieldsArray = $request->query->all()

    private function validate($command): bool|array
    {
        $errors = $this->validator->validate($command);
        if (count($errors) > 0) {
            $errorsArray = ['Errors' => 'fields and messages'];
            foreach ($errors as $error) {
                $field = preg_replace(['/\]\[/', '/\[|\]/'], ['.', ''], $error->getPropertyPath());
                array_push($errorsArray, ['field' => $field, 'message' => $error->getMessage()]);
            }

            return $errorsArray;
        }

        return false;
    }

    protected function fieldsToDto($fieldsArray, $dto, $uuid = false)
    {
        $fieldsJson = $this->serializer->serialize($fieldsArray, 'json');
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $command = $serializer->deserialize($fieldsJson, $dto, 'json');

        if (!$uuid) {
            $uuid = Uuid::v4()->toRfc4122();
        }

        $command->setUuid($uuid);

        return $command;
    }

    private function returnJsonResponse(array $array, int $status): JsonResponse
    {
        $response = new JsonResponse($array, $status);
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        return $response;
    }

    /**
     * @throws ExceptionInterface
     */
    private function renderResponse($typeController, $command): JsonResponse
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        // Рендер ответа
        if (self::TYPE_CREATE === $typeController) {
            return $this->returnJsonResponse(['uuid' => $command->getUuid()], $this::RESOURCE_CREATED);
        }
        if (self::TYPE_UPDATE === $typeController) {
            $jsonData = $serializer->normalize($command, 'array');
            return $this->returnJsonResponse(['response' => $jsonData], $this::RESOURCE_CREATED);
        }
        if (self::TYPE_DELETE === $typeController) {
            return $this->returnJsonResponse(['uuid' => $command->getUuid()], $this::OK);
        }

        return $this->returnJsonResponse(['response' => $this::UNKNOWN_REQUEST], $this::NOTFOUND);
    }

    protected function validateVO($valueObject): JsonResponse|bool
    {
        // validate
        $errorsArray = $this->validate($valueObject);

        if ($errorsArray) {
            return $this->returnJsonResponse($errorsArray, $this::BAD_REQUEST);
        }
        return true;
    }

    /**
     * @throws ExceptionInterface
     */
    protected function validateDispatchAndRenderJson($command, int $typeController): JsonResponse
    {
        // validate
        $errorsArray = $this->validate($command);

        if ($errorsArray) {
            return $this->returnJsonResponse($errorsArray, $this::BAD_REQUEST);
        }

        // Отправить в очередь
        $this->bus->dispatch($command);

        return $this->renderResponse($typeController, $command);
    }

    protected function getRoles(): array
    {
        return $this->roles;
    }

    protected function setRoles($array)
    {
        $this->roles = $array;
    }
}
