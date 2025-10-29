<!-- frontend/src/App.vue -->
<template>
  <div id="app">
    <header>
      <h1>Cinch E-Commerce</h1>
    </header>

    <!-- Display a success or error message after checkout attempt -->
    <div v-if="checkoutStatus" :class="['checkout-message', checkoutStatus.type]">
      {{ checkoutStatus.message }}
    </div>

    <div class="layout">
      <main>
        <ProductList @add-to-cart="handleAddToCart" />
      </main>
      <aside>
        <ShoppingCart :cart-items="cartItems" />
        <!-- Add the CheckoutForm only if the cart is not empty -->
        <CheckoutForm
          v-if="cartItems.length > 0"
          @checkout="handleCheckout"
          :is-submitting="isSubmitting"
        />
      </aside>
    </div>
  </div>
</template>

<script>
import ProductList from './components/ProductList.vue';
import ShoppingCart from './components/ShoppingCart.vue';
import CheckoutForm from './components/CheckoutForm.vue'; // <-- Import the form
import { checkoutAPI } from './api'; // <-- Import our checkoutAPI instance

export default {
  name: 'App',
  components: {
    ProductList,
    ShoppingCart,
    CheckoutForm, // <-- Register the form
  },
  data() {
    return {
      cartItems: [],
      isSubmitting: false, // For disabling the button during API call
      checkoutStatus: null, // To hold success/error message
    };
  },
  methods: {
    handleAddToCart(product) {
      this.checkoutStatus = null; // Clear any previous message
      const existingItem = this.cartItems.find(item => item.id === product.id);
      if (existingItem) {
        existingItem.quantity++;
      } else {
        this.cartItems.push({ ...product, quantity: 1 });
      }
    },
    async handleCheckout(customerDetails) {
      this.isSubmitting = true;
      this.checkoutStatus = null;

      // 1. Format the data for the API
      const orderPayload = {
        customer_name: customerDetails.customerName,
        customer_email: customerDetails.customerEmail,
        products: this.cartItems.map(item => ({
          product_id: item.id,
          quantity: item.quantity,
        })),
      };

      try {
        // 2. Send the POST request to the Checkout Service
        await checkoutAPI.post('orders', orderPayload);

        // 3. Handle success
        this.checkoutStatus = {
          type: 'success',
          message: 'Order placed successfully! A confirmation email has been sent.',
        };
        this.cartItems = []; // Clear the cart
      } catch (error) {
        // 4. Handle errors
        console.error('Checkout failed:', error);
        this.checkoutStatus = {
          type: 'error',
          message: 'There was a problem placing your order. Please try again.',
        };
      } finally {
        // 5. Re-enable the form button
        this.isSubmitting = false;
      }
    },
  },
};
</script>

<style>
/* ... your existing global styles ... */
body { margin: 0; background-color: #f4f7f6; }
#app { font-family: Avenir, Helvetica, Arial, sans-serif; color: #2c3e50; max-width: 1200px; margin: 0 auto; padding: 20px; }
header { text-align: center; margin-bottom: 2rem; border-bottom: 1px solid #e0e0e0; padding-bottom: 1rem; }
.layout { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }

/* New styles for status messages */
.checkout-message {
  padding: 1rem;
  margin-bottom: 1rem;
  border-radius: 8px;
  text-align: center;
}
.checkout-message.success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}
.checkout-message.error {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}
</style>