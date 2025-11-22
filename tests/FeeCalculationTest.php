<?php

namespace App\Tests;

use App\ApiResource\FeeCalculation;
use Generator;
use PHPUnit\Framework\TestCase;

class FeeCalculationTest extends TestCase
{
    private FeeCalculation $feeCalculation;

    public function getVehicleCommonChoiceTests(): Generator
    {
        yield [398,  39.80,  7.96,  5.00, 100.00,  550.76];
        yield [501,  50.00, 10.02, 10.00, 100.00,  671.02];
        yield [ 57,  10.00,  1.14,  5.00, 100.00,  173.14];
        yield [1100, 50.00, 22.00, 15.00, 100.00, 1287.00];
    }

    public function getVehicleLuxeryChoiceTests(): Generator
    {
        yield [1800, 180.00, 72.00, 15.00, 100.00, 2167.00];
        yield [1000000, 200.00, 40000.00, 20.00, 100.00, 1040320.00];
    }

    /**
     * @dataProvider getVehicleCommonChoiceTests
     */
    public function testVehicleTypeCommonWithProvider(
        $vehiclePrice,
        $feeBasicExpected,
        $feeSpecialExpected,
        $feeAssociationExpected,
        $feeStorageExpected,
        $totalExpected
    ): void {
        $vehicleType = 'Common';

        $this->runTestVehicleTypeWithProvider(
            $vehicleType,
            $vehiclePrice,
            $feeBasicExpected,
            $feeSpecialExpected,
            $feeAssociationExpected,
            $feeStorageExpected,
            $totalExpected
        );
    }

    /**
     * @dataProvider getVehicleLuxeryChoiceTests
     */
    public function testVehicleTypeLuxuryWithProvider(
        $vehiclePrice,
        $feeBasicExpected,
        $feeSpecialExpected,
        $feeAssociationExpected,
        $feeStorageExpected,
        $totalExpected
    ): void {
        $vehicleType = 'Luxury';

        $this->runTestVehicleTypeWithProvider(
            $vehicleType,
            $vehiclePrice,
            $feeBasicExpected,
            $feeSpecialExpected,
            $feeAssociationExpected,
            $feeStorageExpected,
            $totalExpected
        );
    }

    public function runTestVehicleTypeWithProvider(
        $vehicleType,
        $vehiclePrice,
        $feeBasicExpected,
        $feeSpecialExpected,
        $feeAssociationExpected,
        $feeStorageExpected,
        $totalExpected
    ): void {
        $this->feeCalculation = new FeeCalculation();

        $feeBasic = $this->feeCalculation->calculateFeeBasic($vehiclePrice, $vehicleType);
        $this->assertSame($feeBasicExpected, $feeBasic);

        $feeSpecial = $this->feeCalculation->calculateFeeSpecial($vehiclePrice, $vehicleType);
        $this->assertSame($feeSpecialExpected, $feeSpecial);

        $feeAssociation = $this->feeCalculation->calculateFeeAssociation($vehiclePrice);
        $this->assertSame($feeAssociationExpected, $feeAssociation);

        $feeStorage = $this->feeCalculation->getFeeStorage();
        $this->assertSame($feeStorageExpected, $feeStorage);

        $total = $this->feeCalculation->calculateTotalPrice(
            $vehiclePrice,
            $feeBasic,
            $feeSpecial,
            $feeAssociation,
            $feeStorage
        );
        $this->assertEquals($totalExpected, $total);
    }
}
