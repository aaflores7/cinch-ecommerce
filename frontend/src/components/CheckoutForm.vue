<!-- frontend/src/components/CheckoutForm.vue -->
<template>
    <div class="checkout-form">
      <h3>Checkout</h3>
      <!-- The .prevent modifier stops the page from reloading on submit -->
      <form @submit.prevent="submitOrder">
        <div class="form-group">
          <label for="customerName">Name:</label>
          <input id="customerName" v-model="customerName" type="text" required>
        </div>
        <div class="form-group">
          <label for="customerEmail">Email:</label>
          <input id="customerEmail" v-model="customerEmail" type="email" required>
        </div>
        <!-- The button is disabled if the form is busy submitting -->
        <button type="submit" :disabled="isSubmitting">
          {{ isSubmitting ? 'Placing Order...' : 'Place Order' }}
        </button>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    name: 'CheckoutForm',
    props: {
      isSubmitting: {
        type: Boolean,
        default: false,
      }
    },
    data() {
      return {
        customerName: '',
        customerEmail: '',
      };
    },
    methods: {
      submitOrder() {
        // When the form is submitted, emit a 'checkout' event upwards
        // and pass the customer details as the payload.
        this.$emit('checkout', {
          customerName: this.customerName,
          customerEmail: this.customerEmail,
        });
      },
    },
  };
  </script>
  
  <style scoped>
  .checkout-form {
    margin-top: 2rem;
    border-top: 2px solid #e0e0e0;
    padding-top: 1.5rem;
  }
  .form-group {
    margin-bottom: 1rem;
  }
  .form-group label {
    display: block;
    margin-bottom: 0.5rem;
  }
  .form-group input {
    width: 100%;
    padding: 8px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
  }
  button {
    width: 100%;
    background-color: #27ae60;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1.1rem;
    transition: background-color 0.2s;
  }
  button:hover {
    background-color: #229954;
  }
  button:disabled {
    background-color: #bdc3c7;
    cursor: not-allowed;
  }
  </style>