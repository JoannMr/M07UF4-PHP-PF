import { createApp } from 'vue';
import LoginForm from './components/LoginForm.vue';
import ProductsTable from './components/ProductsTable.vue';

createApp(LoginForm).mount('#login');
createApp(ProductsTable).mount('#products');