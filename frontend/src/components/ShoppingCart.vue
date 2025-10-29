<!-- frontend/src/components/ShoppingCart.vue -->
<template>
    <div class="shopping-cart">
      <h2>Shopping Cart</h2>
      <div v-if="cartItems.length === 0" class="empty-cart">
        Your cart is empty.
      </div>
      <div v-else>
        <ul>
          <li v-for="item in cartItems" :key="item.id" class="cart-item">
            <span>{{ item.name }}</span>
            <span>{{ item.quantity }} x ${{ item.price }}</span>
            <span>${{ (item.quantity * item.price).toFixed(2) }}</span>
          </li>
        </ul>
        <hr>
        <div class="cart-total">
          <strong>Total: ${{ cartTotal.toFixed(2) }}</strong>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'ShoppingCart',
    // 'props' are how a parent component (App.vue) sends data to this child.
    props: {
      cartItems: {
        type: Array,
        required: true,
      },
    },
    // 'computed' properties automatically recalculate when their dependencies change.
    computed: {
      cartTotal() {
        // The reduce function sums up the total cost of all items in the cart.
        return this.cartItems.reduce((total, item) => {
          return total + (item.price * item.quantity);
        }, 0);
      },
    },
  };
  </script>
  
  <style scoped>
  .shopping-cart {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 16px;
    background-color: #ffffff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  }
  .empty-cart {
    text-align: center;
    color: #7f8c8d;
    padding: 1rem 0;
  }
  .cart-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
  }
  .cart-total {
    text-align: right;
    font-size: 1.25rem;
    margin-top: 1rem;
  }
  ul {
    list-style-type: none;
    padding: 0;
  }
  </style>