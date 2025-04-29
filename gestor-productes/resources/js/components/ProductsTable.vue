<template>
    <div class="dashboard-container">
        <!-- Header con logo y botón de logout -->
        <header class="dashboard-header">
            <div class="brand">
                <i class="fas fa-box-open brand-icon"></i>
                <h1 class="brand-name">Gestor de Productos</h1>
            </div>
            <button @click="logout" class="logout-button">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar sesión</span>
            </button>
        </header>

        <div class="dashboard-content">
            <div class="section-header">
                <h2 class="section-title">Listado de productos</h2>
                <div v-if="esAdmin" class="section-actions">
                    <button @click="mostrarFormulario = true" class="action-button create-button">
                        <i class="fas fa-plus"></i>
                        <span>Crear nuevo producto</span>
                    </button>
                </div>
            </div>

            <!-- Loader mientras carga -->
            <div v-if="cargando" class="loader-container">
                <div class="loader"></div>
                <p>Cargando productos...</p>
            </div>

            <!-- Tabla de productos (nuevo diseño) -->
            <div v-else class="products-list-container">
                <div class="products-table-header">
                    <div class="header-cell nombre-cell" @click="ordenarPor('name')">
                        NOMBRE
                        <i class="fas" :class="orden === 'name' ? (ascendente ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort'"></i>
                    </div>
                    <div class="header-cell precio-cell" @click="ordenarPor('price')">
                        PRECIO
                        <i class="fas" :class="orden === 'price' ? (ascendente ? 'fa-sort-up' : 'fa-sort-down') : 'fa-sort'"></i>
                    </div>
                    <div v-if="esAdmin" class="header-cell acciones-cell">
                        ACCIONES
                    </div>
                </div>
                
                <div class="products-table-body">
                    <div v-for="product in productosOrdenados" :key="product.id" class="product-row">
                        <div class="product-cell nombre-cell">{{ product.name }}</div>
                        <div class="product-cell precio-cell">{{ Number(product.price).toFixed(2) }} €</div>
                        <div v-if="esAdmin" class="product-cell acciones-cell">
                            <button @click="prepararEdicion(product)" class="action-icon edit-icon" title="Editar producto">
                                <i class="fas fa-pen-to-square"></i>
                            </button>
                            <button @click="eliminarProducto(product.id)" class="action-icon delete-icon" title="Eliminar producto">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div v-if="productosOrdenados.length === 0" class="empty-products">
                        No hay productos disponibles
                    </div>
                </div>
            </div>

            <div v-if="error" class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ error }}</span>
            </div>
            
            <div v-if="mensaje" class="success-message">
                <i class="fas fa-check-circle"></i>
                <span>{{ mensaje }}</span>
            </div>
        </div>

        <!-- Modal formulario -->
        <div v-if="mostrarFormulario" class="modal-overlay">
            <div class="modal-container">
                <div class="modal-header">
                    <h3 class="modal-title">{{ modoEdicion ? 'Editar producto' : 'Nuevo producto' }}</h3>
                    <button @click="cancelarFormulario" class="close-button">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form @submit.prevent="modoEdicion ? actualizarProducto() : crearProducto()" class="product-form">
                    <div class="form-group">
                        <label for="product-name" class="form-label">Nombre</label>
                        <div class="input-container">
                            <i class="fas fa-tag input-icon"></i>
                            <input 
                                v-model="nuevoProducto.name" 
                                id="product-name"
                                type="text" 
                                class="form-input" 
                                required
                                placeholder="Nombre del producto"
                            >
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="product-description" class="form-label">Descripción</label>
                        <div class="input-container">
                            <i class="fas fa-align-left input-icon"></i>
                            <input 
                                v-model="nuevoProducto.description" 
                                id="product-description"
                                type="text" 
                                class="form-input"
                                placeholder="Descripción del producto"
                            >
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="product-price" class="form-label">Precio (€)</label>
                        <div class="input-container">
                            <i class="fas fa-euro-sign input-icon"></i>
                            <input 
                                v-model.number="nuevoProducto.price" 
                                id="product-price"
                                type="number" 
                                step="0.01" 
                                class="form-input" 
                                required
                                placeholder="0.00"
                            >
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" @click="cancelarFormulario" class="cancel-button">
                            <i class="fas fa-times"></i>
                            <span>Cancelar</span>
                        </button>
                        <button type="submit" class="submit-button">
                            <i class="fas" :class="modoEdicion ? 'fa-save' : 'fa-plus-circle'"></i>
                            <span>{{ modoEdicion ? 'Actualizar' : 'Crear' }}</span>
                        </button>
                    </div>
                    
                    <div v-if="error" class="form-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ error }}</span>
                    </div>
                    <div v-if="mensaje" class="form-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ mensaje }}</span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const productos = ref([]);
const error = ref('');
const mensaje = ref('');
const orden = ref('name');
const ascendente = ref(true);
const mostrarFormulario = ref(false);
const modoEdicion = ref(false);
const productoEditandoId = ref(null);
const cargando = ref(true); // ⏳ Loader mientras carga productos

const nuevoProducto = ref({
    name: '',
    description: '',
    price: null
});

const apiBase = 'http://localhost/gestor-productes/public/api/';
const esAdmin = localStorage.getItem('role') === 'admin';

const logout = () => {
    if (confirm('¿Seguro que quieres cerrar sesión?')) {
        localStorage.removeItem('token');
        localStorage.removeItem('role');
        window.location.href = window.location.origin + '/gestor-productes/public/login';
    }
};

const cargarProductos = async () => {
    cargando.value = true;
    error.value = '';
    try {
        const token = localStorage.getItem('token');
        const response = await axios.get(apiBase + 'products', {
            headers: {
                Authorization: 'Bearer ' + token
            }
        });
        productos.value = response.data;
    } catch (err) {
        error.value = 'Error al cargar los productos. Asegúrate de estar logueado.';
        console.error(err);
    } finally {
        cargando.value = false;
    }
};

const ordenarPor = (campo) => {
    if (orden.value === campo) {
        ascendente.value = !ascendente.value;
    } else {
        orden.value = campo;
        ascendente.value = true;
    }
};

const productosOrdenados = computed(() => {
    return [...productos.value].sort((a, b) => {
        const campoA = a[orden.value];
        const campoB = b[orden.value];
        return (campoA < campoB ? (ascendente.value ? -1 : 1) : (campoA > campoB ? (ascendente.value ? 1 : -1) : 0));
    });
});

const crearProducto = async () => {
    error.value = '';
    mensaje.value = '';
    try {
        const token = localStorage.getItem('token');
        const response = await axios.post(apiBase + 'products', nuevoProducto.value, {
            headers: {
                Authorization: 'Bearer ' + token
            }
        });
        productos.value.push(response.data);
        mensaje.value = 'Producto creado correctamente.';
        cancelarFormulario();
    } catch (err) {
        error.value = 'Error al crear el producto.';
        console.error(err);
    }
};

const prepararEdicion = (product) => {
    modoEdicion.value = true;
    mostrarFormulario.value = true;
    productoEditandoId.value = product.id;
    nuevoProducto.value = { ...product };
};

const actualizarProducto = async () => {
    error.value = '';
    mensaje.value = '';
    try {
        const token = localStorage.getItem('token');
        const response = await axios.put(apiBase + 'products/' + productoEditandoId.value, nuevoProducto.value, {
            headers: {
                Authorization: 'Bearer ' + token
            }
        });
        const index = productos.value.findIndex(p => p.id === productoEditandoId.value);
        productos.value[index] = response.data;
        mensaje.value = 'Producto actualizado correctamente.';
        cancelarFormulario();
    } catch (err) {
        error.value = 'Error al actualizar el producto.';
        console.error(err);
    }
};

const eliminarProducto = async (id) => {
    if (!confirm('¿Seguro que quieres eliminar este producto?')) return;
    try {
        const token = localStorage.getItem('token');
        await axios.delete(apiBase + 'products/' + id, {
            headers: {
                Authorization: 'Bearer ' + token
            }
        });
        productos.value = productos.value.filter(p => p.id !== id);
        mensaje.value = 'Producto eliminado correctamente.';
    } catch (err) {
        error.value = 'Error al eliminar el producto.';
        console.error(err);
    }
};

const cancelarFormulario = () => {
    mostrarFormulario.value = false;
    modoEdicion.value = false;
    productoEditandoId.value = null;
    nuevoProducto.value = { name: '', description: '', price: null };
    mensaje.value = '';
    error.value = '';
};

onMounted(cargarProductos);
</script>

<style scoped>
/* Estilos base */
.dashboard-container {
    font-family: 'Poppins', sans-serif;
    color: #2c3e50;
    min-height: 100vh;
    background-color: #f8f9fa;
}

/* Header */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.brand {
    display: flex;
    align-items: center;
    gap: 12px;
}

.brand-icon {
    color: #3498db;
    font-size: 24px;
}

.brand-name {
    font-size: 20px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.logout-button {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background-color: #f8f9fa;
    color: #e74c3c;
    border: 1px solid #e74c3c;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.logout-button:hover {
    background-color: #e74c3c;
    color: white;
}

/* Dashboard content */
.dashboard-content {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.section-title {
    font-size: 26px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.section-actions {
    display: flex;
    gap: 12px;
}

.action-button {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.create-button {
    background-color: #4ade80;
    color: white;
    border: none;
}

.create-button:hover {
    background-color: #22c55e;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Loader */
.loader-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 200px;
}

.loader {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 16px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Nuevo estilo de tabla inspirado en la imagen */
.products-list-container {
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    overflow: hidden;
    margin-bottom: 30px;
}

.products-table-header {
    display: flex;
    background-color: #d1fae5;
    border-bottom: 1px solid #e5e7eb;
}

.header-cell {
    padding: 16px 20px;
    font-weight: 600;
    color: #2c3e50;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.header-cell:hover {
    background-color: #a7f3d0;
}

.nombre-cell {
    flex: 3;
    text-align: left;
}

.precio-cell {
    flex: 1;
    justify-content: flex-start;
}

.acciones-cell {
    flex: 1;
    justify-content: center;
}

.products-table-body {
    background-color: white;
}

.product-row {
    display: flex;
    border-bottom: 1px solid #e5e7eb;
    transition: background-color 0.2s;
}

.product-row:hover {
    background-color: #f0fdf4;
}

.product-cell {
    padding: 16px 20px;
    display: flex;
    align-items: center;
}

.product-cell.nombre-cell {
    flex: 3;
    font-weight: 500;
    color: #2c3e50;
}

.product-cell.precio-cell {
    flex: 1;
    font-weight: 600;
    color: #3498db;
}

.product-cell.acciones-cell {
    flex: 1;
    justify-content: center;
    gap: 12px;
}

.action-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    background-color: transparent;
}

.edit-icon {
    color: #3498db;
}

.edit-icon:hover {
    background-color: #3498db;
    color: white;
}

.delete-icon {
    color: #e74c3c;
}

.delete-icon:hover {
    background-color: #e74c3c;
    color: white;
}

.empty-products {
    text-align: center;
    color: #7f8c8d;
    padding: 40px 0;
}

/* Message alerts */
.error-message, .success-message {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 14px 20px;
    border-radius: 8px;
    margin-top: 20px;
}

.error-message {
    background-color: #fff5f5;
    color: #e53e3e;
}

.success-message {
    background-color: #f0fff4;
    color: #2ecc71;
}

/* Modal styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-container {
    width: 100%;
    max-width: 500px;
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    overflow: hidden;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #eaeaea;
}

.modal-title {
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.close-button {
    background: none;
    border: none;
    color: #7f8c8d;
    font-size: 18px;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: background-color 0.2s;
}

.close-button:hover {
    background-color: #f8f9fa;
    color: #e74c3c;
}

/* Form styles */
.product-form {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-label {
    font-size: 14px;
    font-weight: 600;
    color: #2c3e50;
}

.input-container {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #7f8c8d;
}

.form-input {
    width: 100%;
    padding: 12px 16px 12px 48px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.form-input:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    outline: none;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 10px;
}

.cancel-button, .submit-button {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.cancel-button {
    background-color: #f8f9fa;
    color: #7f8c8d;
    border: 1px solid #e0e0e0;
}

.cancel-button:hover {
    background-color: #e0e0e0;
}

.submit-button {
    background-color: #3498db;
    color: white;
    border: none;
}

.submit-button:hover {
    background-color: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.form-error, .form-success {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    margin-top: 10px;
}

.form-error {
    background-color: #fff5f5;
    color: #e53e3e;
}

.form-success {
    background-color: #f0fff4;
    color: #2ecc71;
}
</style>
