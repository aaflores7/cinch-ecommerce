// frontend/src/api.js
import axios from 'axios';

// Since our services are on different ports, we create separate instances
export const catalogAPI = axios.create({
    baseURL: 'http://localhost:8001/api/'
});

export const checkoutAPI = axios.create({
    baseURL: 'http://localhost:8002/api/'
});

