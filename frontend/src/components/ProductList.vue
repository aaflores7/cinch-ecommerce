<!-- frontend/src/components/ProductList.vue -->
<template>
  <div class="product-list">
    <h2>Our Products</h2>
    <!-- Show a loading message while fetching data -->
    <div v-if="loading" class="loading">Loading products...</div>

    <!-- Once loaded, display the product grid -->
    <div v-else class="product-grid">
      <!-- Loop through each product in the 'products' array -->
      <div v-for="product in products" :key="product.id" class="product-card">
        <img :src="product.image_url" alt="product image">
        <h3>{{ product.name }}</h3>
        <p class="price">${{ product.price }}</p>
        <p class="description">{{ product.description }}</p>
        <!-- We will add a button here in the next step -->
        <button @click="$emit('add-to-cart', product)">Add to Cart</button>
      </div>
    </div>
    <div v-if="error" class="error">{{ error }}</div>
  </div>
</template>

<script>
// Import our pre-configured catalogAPI instance
import { catalogAPI } from '../api';

export default {
  name: 'ProductList',
  data() {
    return {
      products: [], // This array will hold our product data
      loading: true, // A flag to show the loading message
      error: null,   // To store any potential error messages
    };
  },
  // This 'mounted' method is a lifecycle hook that runs automatically
  // when the component is first added to the page.
  async mounted() {
    try {
      // Make a GET request to http://localhost:8001/api/products
      const response = await catalogAPI.get('products');
      // Store the data from the response in our 'products' array
      this.products = response.data;
    } catch (err) {
      console.error("Failed to fetch products:", err);
      this.error = 'Failed to load products. Please try again later.';
    } finally {
      // This runs whether the request succeeded or failed
      this.loading = false;
    }
  }
}
</script>

<style scoped>
/* 'scoped' means these styles only apply to this component */
.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
}
.product-card {
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 16px;
  text-align: center;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  transition: transform 0.2s;
}
.product-card:hover {
  transform: translateY(-5px);
}
.product-card img {
  max-width: 100%;
  height: 150px;
  object-fit: cover;
  margin-bottom: 1rem;
}
.price {
  font-size: 1.25rem;
  font-weight: bold;
  color: #3498db;
}
.description {
  font-size: 0.9rem;
  color: #7f8c8d;
}
.loading, .error {
  text-align: center;
  padding: 2rem;
  font-size: 1.2rem;
}
button {
  background-color: #3498db;
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
  transition: background-color 0.2s;
}
button:hover {
  background-color: #2980b9;
}
</style>