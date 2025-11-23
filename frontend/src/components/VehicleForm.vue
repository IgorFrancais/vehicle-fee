<template>
  <div class="form-container">
    <h2>Vehicle Fee Calculator</h2>
    <form @submit.prevent="calculateFee">
      <div class="form-input-data">
        <div class="vehicle-price">
          <label class="vehicle-price-label" for="price">Vehicle Price:</label>
          <input type="number" v-model.number="vehiclePrice" id="price" class="vehicle-price-number"/>
          <!-- Error shows immediately while typing -->
          <span v-if="error" class="error">{{ error }}</span>
        </div>

        <div class="vehicle-type">
          <label class="vehicle-type-label" for="type">Vehicle Type:</label>
          <select v-model="vehicleType" id="type" class="vehicle-type-number">
            <option value="common">Common</option>
            <option value="luxury">Luxury</option>
          </select>
        </div>

        <button type="submit">Calculate</button>
      </div>

      <div class="calculation">
        <div v-if="feeBasic !== 0">
          <h3 class="calculation-title">Calculated Fee</h3>
          <p><span class="label">Fee Basic:</span><span class="value">{{ feeBasic.toFixed(2) }}</span></p>
        </div>
        <div v-if="feeSpecial !== 0">
          <p><span class="label">Fee Special:</span><span class="value">{{ feeSpecial.toFixed(2) }}</span></p>
        </div>
        <div v-if="feeAssociation !== 0">
          <p><span class="label">Fee Association:</span><span class="value">{{ feeAssociation.toFixed(2) }}</span></p>
        </div>
        <div v-if="feeStorage !== 0">
          <p><span class="label">Fee Storage:</span><span class="value">{{ feeStorage.toFixed(2) }}</span></p>
        </div>
        <div v-if="totalPrice !== 0">
          <p><span class="label">Total Price:</span><span class="value">{{ totalPrice.toFixed(2) }}</span></p>
        </div>

      </div>
    </form>
  </div>
</template>

<script setup>
import { useVehicleFeeCalculator } from '../assets/js/form';

const {
  vehiclePrice,
  vehicleType,
  feeBasic,
  feeSpecial,
  feeAssociation,
  feeStorage,
  totalPrice,
  error,
  calculateFee
} = useVehicleFeeCalculator()
</script>

<style src="../assets/styles/form.css" />
