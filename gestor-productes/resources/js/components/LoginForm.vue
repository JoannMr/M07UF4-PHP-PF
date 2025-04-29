<template>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1 class="login-title">Iniciar sesión</h1>
                <p class="login-subtitle">Accede a tu cuenta para gestionar productos</p>
            </div>
            
            <form @submit.prevent="login" class="login-form">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-container">
                        <i class="fas fa-envelope input-icon"></i>
                        <input v-model="email" type="email" id="email" class="form-input" required 
                            placeholder="Introduce tu email">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-container">
                        <i class="fas fa-lock input-icon"></i>
                        <input v-model="password" type="password" id="password" class="form-input" required
                            placeholder="Introduce tu contraseña">
                    </div>
                </div>
                
                <div v-if="error" class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ error }}</span>
                </div>
                
                <button type="submit" class="login-button">
                    <span>Iniciar sesión</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const email = ref('');
const password = ref('');
const error = ref('');
const apiBase = 'http://localhost/gestor-productes/public/api/';

const login = async () => {
    error.value = '';
    try {
        console.log('Intentando conectar a: ' + apiBase + 'login');
        const response = await axios.post(apiBase + 'login', {
            email: email.value,
            password: password.value,
        });

        localStorage.setItem('token', response.data.access_token);
        localStorage.setItem('role', response.data.role);
        window.location.href = window.location.origin + '/gestor-productes/public/products';
    } catch (err) {
        if (err.response && err.response.data) {
            error.value = 'Credenciales incorrectas.';
        } else {
            error.value = 'Error al conectar con el servidor.';
        }
    }
};
</script>

<style scoped>
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 20px;
}

.login-card {
    width: 100%;
    max-width: 450px;
    background-color: white;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    padding: 40px;
}

.login-header {
    text-align: center;
    margin-bottom: 32px;
}

.login-title {
    color: #2c3e50;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 8px;
}

.login-subtitle {
    color: #7f8c8d;
    font-size: 16px;
}

.login-form {
    display: flex;
    flex-direction: column;
    gap: 24px;
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

.error-message {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background-color: #fff5f5;
    color: #e53e3e;
    border-radius: 8px;
    font-size: 14px;
}

.login-button {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    padding: 14px 24px;
    background: linear-gradient(to right, #3498db, #2980b9);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.login-button:hover {
    background: linear-gradient(to right, #2980b9, #3498db);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.login-button:active {
    transform: translateY(0);
}
</style>
