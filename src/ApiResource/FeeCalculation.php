<?php

namespace App\ApiResource;

class FeeCalculation
{
    public const string VEHICLE_TYPE_COMMON = 'common';
    private const string VEHICLE_TYPE_LUXURY = 'luxury';
    private const int BASIC_FEE_PERCENT = 10;
    private const int BASIC_FEE_MIN_COMMON = 10;
    private const int BASIC_FEE_MAX_COMMON = 50;
    private const int BASIC_FEE_MIN_LUXURY = 25;
    private const int BASIC_FEE_MAX_LUXURY = 200;
    private const int SPECIAL_FEE_PERCENT_COMMON = 2;
    private const int SPECIAL_FEE_PERCENT_LUXURY = 4;
    private const int ASSOCIATION_FEE_1_500 = 5;
    private const int ASSOCIATION_FEE_500_1000 = 10;
    private const int ASSOCIATION_FEE_1000_3000 = 15;
    private const int ASSOCIATION_FEE_3000_OVER = 20;
    private const int STORAGE_FEE = 100;

    public function calculateFeeBasic(float $vehiclePrice, string $vehicleType): float
    {
        $feeBasic = $this->numberFormat($vehiclePrice, self::BASIC_FEE_PERCENT);

        return match ($vehicleType) {
            self::VEHICLE_TYPE_LUXURY => $this->calculateFeeBasicLuxury($feeBasic),
            self::VEHICLE_TYPE_COMMON => $this->calculateFeeBasicCommon($feeBasic),
            default => 0,
        };
    }

    public function calculateFeeSpecial(float $vehiclePrice, string $vehicleType): float
    {
        $feeSpecialPercent = match ($vehicleType) {
            self::VEHICLE_TYPE_COMMON => self::SPECIAL_FEE_PERCENT_COMMON,
            self::VEHICLE_TYPE_LUXURY => self::SPECIAL_FEE_PERCENT_LUXURY,
            default => 0,
        };

        return $this->numberFormat($vehiclePrice, $feeSpecialPercent);
    }

    public function calculateFeeAssociation($vehiclePrice): float
    {
        return match (true) {
            $vehiclePrice < 500 => self::ASSOCIATION_FEE_1_500,
            $vehiclePrice < 1000 => self::ASSOCIATION_FEE_500_1000,
            $vehiclePrice < 3000 => self::ASSOCIATION_FEE_1000_3000,
            default => self::ASSOCIATION_FEE_3000_OVER,
        };
    }

    public function getFeeStorage(): float
    {
        return self::STORAGE_FEE;
    }

    public function calculateTotalPrice(
        float $vehiclePrice,
        float $feeBasic,
        float $feeSpecial,
        float $feeAssociation,
        float $feeStorage,
    ): float {
        return $vehiclePrice + $feeBasic + $feeSpecial + $feeAssociation + $feeStorage;
    }

    private function calculateFeeBasicLuxury(float $feeBasic): float
    {
        return match (true) {
            $feeBasic < self::BASIC_FEE_MIN_LUXURY => self::BASIC_FEE_MIN_LUXURY,
            $feeBasic > self::BASIC_FEE_MAX_LUXURY => self::BASIC_FEE_MAX_LUXURY,
            default => $feeBasic,
        };
    }

    private function calculateFeeBasicCommon(float $feeBasic): float
    {
        return match (true) {
            $feeBasic < self::BASIC_FEE_MIN_COMMON => self::BASIC_FEE_MIN_COMMON,
            $feeBasic > self::BASIC_FEE_MAX_COMMON => self::BASIC_FEE_MAX_COMMON,
            default => $feeBasic,
        };
    }

    private function numberFormat(float $value, int $percent): float
    {
        return number_format(
            $value * ($percent / 100),
            2,
            '.',
            '');
    }
}
