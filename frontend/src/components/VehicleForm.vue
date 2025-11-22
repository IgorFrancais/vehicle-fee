<template>
  <div class="form-container">
    <h2>Vehicle Fee Calculator</h2>
    <form @submit.prevent="calculateFee">
      <div>
        <label for="price">Vehicle Price:</label>
        <input type="number" v-model="vehiclePrice" id="price" />
      </div>

      <div>
        <label for="type">Vehicle Type:</label>
        <select v-model="vehicleType" id="type">
          <option disabled value="">-- Select a type --</option>
          <option value="common">Common</option>
          <option value="luxury">Luxury</option>
        </select>
      </div>

      <button type="submit">Calculate</button>

      <div v-if="feeBasic !== null">
        <h3>Calculated Fee</h3>
        <p>Fee Basic: {{ feeBasic }}</p>
      </div>
      <div v-if="feeSpecial !== null">
        <p>Fee Special: {{ feeSpecial }}</p>
      </div>
      <div v-if="feeAssociation !== null">
        <p>Fee Association: {{ feeAssociation }}</p>
      </div>
      <div v-if="feeStorage !== null">
        <p>Fee Storage: {{ feeStorage }}</p>
      </div>
      <div v-if="totalPrice !== null">
        <p>TotaPrice: {{ totalPrice }}</p>
      </div>

    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const vehiclePrice = ref(0)
const vehicleType = ref('')
const feeBasic = ref(null)
const feeSpecial = ref(null)
const feeAssociation = ref(null)
const feeStorage = ref(null)
const totalPrice = ref(null)

async function calculateFee() {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/calculate', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        vehiclePrice: vehiclePrice.value,
        vehicleType: vehicleType.value
      })
    })
    const data = await response.json()
    feeBasic.value = data.feeBasic
    feeSpecial.value = data.feeSpecial
    feeAssociation.value = data.feeAssociation
    feeStorage.value = data.feeStorage
    totalPrice.value = data.totalPrice
  } catch (error) {
    console.error('Error calculating fee:', error)
  }
}
</script>

<style scoped>
.form-container {
  max-width: 400px;
  margin: auto;
  padding: 20px;
  border: 1px solid #ddd;
}
</style>
