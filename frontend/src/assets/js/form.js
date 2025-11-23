import { ref, computed } from 'vue'

export function useVehicleFeeCalculator() {
  const vehiclePrice = ref(0)
  const vehicleType = ref('common')
  const feeBasic = ref(0)
  const feeSpecial = ref(0)
  const feeAssociation = ref(0)
  const feeStorage = ref(0)
  const totalPrice = ref(0)
  const calcUrl = 'http://127.0.0.1:8000/api/calculate'

  const error = computed(() => {
    if (vehiclePrice.value === null || vehiclePrice.value === "") {
      return "* Vehicle price is required"
    }
    if (vehiclePrice.value <= 0) {
      return "* Vehicle price must be greater than 0"
    }

    return ""
  })

  async function calculateFee() {
    if (!error.value) {
      try {
        const response = await fetch(calcUrl, {
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
      } catch (err) {
        console.error('Error calculating fee:', err)
      }
    }
  }

  return {
    vehiclePrice,
    vehicleType,
    feeBasic,
    feeSpecial,
    feeAssociation,
    feeStorage,
    totalPrice,
    error,
    calculateFee
  }
}
