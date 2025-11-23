<?php

namespace App\Controller;

use App\ApiResource\FeeCalculation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FeeController extends AbstractController
{
    public function __construct(private readonly FeeCalculation $feeCalculation)
    {
    }

    #[Route('/api/calculate', methods: ['POST'])]
    public function calculate(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $vehiclePrice = $data['vehiclePrice'] ?? 0.0;
        $vehicleType = strtolower($data['vehicleType'] ?? FeeCalculation::VEHICLE_TYPE_COMMON);

        $feeBasic = $this->feeCalculation->calculateFeeBasic($vehiclePrice, $vehicleType);
        $feeSpecial = $this->feeCalculation->calculateFeeSpecial($vehiclePrice, $vehicleType);
        $feeAssociation = $this->feeCalculation->calculateFeeAssociation($vehiclePrice);
        $feeStorage = $this->feeCalculation->getFeeStorage();

        $totalPrice = $this->feeCalculation->calculateTotalPrice(
            $vehiclePrice,
            $feeBasic,
            $feeSpecial,
            $feeAssociation,
            $feeStorage
        );

        return new JsonResponse([
            'feeBasic' => $feeBasic,
            'feeSpecial' => $feeSpecial,
            'feeAssociation' => $feeAssociation,
            'feeStorage' => $feeStorage,
            'totalPrice' => $totalPrice,
        ]);
    }
}
