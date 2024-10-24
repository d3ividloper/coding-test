<?php
declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\UseCase\DiscountCalculationRequest;
use App\Application\UseCase\DiscountCalculationUseCase;
use App\Domain\Exception\DomainException;
use App\Domain\Exception\GenericException;
use App\Domain\Exception\InvalidArgumentException;
use Exception;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class DiscountController extends AbstractController
{
    public function __construct(
        private DiscountCalculationUseCase $discountCalculationUseCase,
        private LoggerInterface $logger,
        private SerializerInterface $serializer
    ){}

    #[Route('/discount', name:'discount_create', methods: ['POST'])]
    public function calculateDiscount(Request $request): JsonResponse
    {
        try {
            if (!json_validate($request->getContent())) {
                throw new InvalidArgumentException("provided input JSON not valid");
            }
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

           $this->validateInputData($data);

            $discountCalculationRequest = new DiscountCalculationRequest(
                orderId: (int)$data['id'],
                customerId: (int)$data['customer-id'],
                items: $data['items'],
                totalAmount: (float)$data['total']
            );

            $discounts = $this->discountCalculationUseCase->calculateDiscount($discountCalculationRequest);
            $data = [
                'order_discounts' => $discounts
            ];
            $data = $this->serializer->serialize($data, 'json', ['groups' => ['view', 'amount', 'freeItems']]);
            return JsonResponse::fromJsonString($data, Response::HTTP_OK, ["Content-Type" => "application/json"]);

        } catch (InvalidArgumentException|DomainException $e) {
            return $this->domainExceptionJsonResponse($e);
        } catch (JsonException $e) {
            return $this->exceptionJsonResponse($e);
        }
    }

    private function validateInputData(array $data): void
    {
        if (!array_key_exists('items', $data) || !is_array($data['items']) || empty($data['items'])) {
            throw new InvalidArgumentException("Items data is empty or not present.");
        }

        if (array_key_exists('customer-id', $data) && empty($data['customer-id'])) {
            throw new InvalidArgumentException("Customer id cannot be empty");
        }
    }
    public function exceptionJsonResponse(Exception $e): JsonResponse
    {
        $error = new GenericException($e->getMessage() . '. File: ' . $e->getFile() . ' Line: ' . $e->getLine());
        $data = [
            'errors' => [$error],
        ];
        $data = $this->serializer->serialize($data, 'json', ['groups' => 'error']);

        return JsonResponse::fromJsonString($data, Response::HTTP_INTERNAL_SERVER_ERROR);
    }


    public function domainExceptionJsonResponse(DomainException $e): JsonResponse
    {
        $code = Response::HTTP_BAD_REQUEST;

        if ($e instanceof InvalidArgumentException) {
            $code = Response::HTTP_NOT_FOUND;
        }

        $this->logger->error('domain exception', [
            'domainCode' => $e->getDomainCode(),
            'description' => $e->getDescription(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        $data = [
            'errors' => [$e],
        ];
        $data = $this->serializer->serialize($data, 'json', ['groups' => 'error']);

        return JsonResponse::fromJsonString($data, $code);
    }
}
