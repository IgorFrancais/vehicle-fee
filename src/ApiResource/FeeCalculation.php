<?php

namespace App\ApiResource;

use function number_format;

class FeeCalculation
{
    private const VEHICLE_TYPE_COMMON = 'common';
    private const VEHICLE_TYPE_COMMON_TITLE = 'Common';
    private const VEHICLE_TYPE_LUXURY = 'luxury';
    private const VEHICLE_TYPE_LUXURY_TITLE = 'Luxury';
    private const BASIC_FEE_PERCENT = 10;
    private const BASIC_FEE_MIN_COMMON = 10;
    private const BASIC_FEE_MAX_COMMON = 50;
    private const BASIC_FEE_MIN_LUXURY = 25;
    private const BASIC_FEE_MAX_LUXURY = 200;
    private const SPECIAL_FEE_PERCENT_COMMON = 2;
    private const SPECIAL_FEE_PERCENT_LUXURY = 4;
    private const ASSOCIATION_FEE_1_500 = 5;
    private const ASSOCIATION_FEE_500_1000 = 10;
    private const ASSOCIATION_FEE_1000_3000 = 15;
    private const ASSOCIATION_FEE_3000_OVER = 20;
    private const STORAGE_FEE = 100;

    public function calculateFeeBasic($vehiclePrice, $vehicleType): float
    {
        $feeBasic = number_format(
            $vehiclePrice * (self::BASIC_FEE_PERCENT / 100),
            2,
            '.',
            '');

        switch ($vehicleType) {
            case self::VEHICLE_TYPE_LUXURY:
                if ($feeBasic < self::BASIC_FEE_MIN_LUXURY) {
                    return self::BASIC_FEE_MIN_LUXURY;
                }

                if ($feeBasic > self::BASIC_FEE_MAX_LUXURY) {
                    return self::BASIC_FEE_MAX_LUXURY;
                }
                break;
            case self::VEHICLE_TYPE_COMMON:
            default:
                if ($feeBasic < self::BASIC_FEE_MIN_COMMON) {
                    return self::BASIC_FEE_MIN_COMMON;
                }

                if ($feeBasic > self::BASIC_FEE_MAX_COMMON) {
                    return self::BASIC_FEE_MAX_COMMON;
                }
                break;
        }

        return $feeBasic;
    }

    public function calculateFeeSpecial($vehiclePrice, $vehicleType): float
    {
        $feeSpecialPercent = 0;

        if ($vehicleType === self::VEHICLE_TYPE_COMMON) {
            $feeSpecialPercent = self::SPECIAL_FEE_PERCENT_COMMON;
        }

        if ($vehicleType === self::VEHICLE_TYPE_LUXURY) {
            $feeSpecialPercent = self::SPECIAL_FEE_PERCENT_LUXURY;
        }

        return number_format(
            $vehiclePrice * ($feeSpecialPercent / 100),
            2,
            '.',
            '');
    }

    public function calculateFeeAssociation($vehiclePrice): float
    {
        if ($vehiclePrice < 500) {
            return self::ASSOCIATION_FEE_1_500;
        }

        if ($vehiclePrice < 1000) {
            return self::ASSOCIATION_FEE_500_1000;
        }

        if ($vehiclePrice < 3000) {
            return self::ASSOCIATION_FEE_1000_3000;
        }

        return self::ASSOCIATION_FEE_3000_OVER;
    }

    public function getFeeStorage(): float
    {
        return self::STORAGE_FEE;
    }

    public function calculateTotalPrice(
        $vehiclePrice,
        $feeBasic,
        $feeSpecial,
        $feeAssociation,
        $feeStorage
    ): float {
        return $vehiclePrice + $feeBasic + $feeSpecial + $feeAssociation + $feeStorage;
    }
}
