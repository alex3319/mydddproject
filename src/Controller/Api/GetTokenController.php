<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

#[Route('/api')]
class GetTokenController extends AbstractController
{
    /**
     * @OA\Post(
     *     path="/api/gettoken",
     *     summary="Получить токен",
     *     tags={"JWT Токен"},
     *     description="Получить JWT токен",
     *     @OA\RequestBody(
     *          @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  required={"phone", "code"},
     *                  @OA\Property(property="phone", type="string", example="123456789"),
     *                  @OA\Property(property="code", type="string", example="1111"),
     *             ),
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Возвращается токен",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(property="token", type="string"),
     *             ),
     *         )
     *     )
     * )
     *
     * @throws ExceptionInterface
     */

    #[Route('/gettoken', name: 'api_gettoken', methods: ['POST'])]
    public function getToken(
        EntityManagerInterface $entityManager,
        ManagerRegistry $doctrine,
        Request $request,
        JWTTokenManagerInterface $JWTManager
    ): JsonResponse
    {
        $data = $request->toArray();
        if (array_key_exists('phone', $data) && array_key_exists('code', $data)){
            $phone = $data['phone'];
            $code1 = $data['code'];
            $repository = $doctrine->getRepository(User::class);
            $user       = $repository->findOneBy(['phone' => $phone]);
            if ($user){
                $code2 = $user->getConfirmationCode();
                if ( $code1 == $code2) {
                    //return new JsonResponse(['token' => $JWTManager->create($user)]);
                    return new JsonResponse([
                        'token' => $JWTManager->createFromPayload($user, $this->getPayload($user))
                    ]);
                }
            }
            return new JsonResponse(['error' => 'Неправильный номер телефона и код']);
        } else {
            return new JsonResponse(['error' => 'Нeуказан номер телефона и код']);
        }
        return new JsonResponse(['error' => 'Нeизвестная ошибка']);
    }

    private function getPayload($user)
    {
        return $payload = [
            'roles' => $user->getRoles(),
        ];
    }
}
