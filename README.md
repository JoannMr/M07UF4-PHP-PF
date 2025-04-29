# 📦 Gestor de Productes amb Autenticació i Interfície Dinàmica

> **Mòdul:** MP07 - Desenvolupament web en entorn servidor  
> **UF:** UF4  
> **Professor:** Carlos Vacas  
> **Alumne:** Joan Merino Serrano  
> **Data límit d’entrega:** 28/04/2025

---

## 📝 Descripció

Aplicació de gestió de productes desenvolupada amb Laravel, Laravel Sanctum i Vue.js.  
El projecte es divideix en:

- **Backend (API RESTful)** amb autenticació per token, control d’accés per rols i documentació amb Swagger.
- **Frontend (Laravel Blade + Vue.js + Vite)** per a la gestió i visualització dinàmica dels productes segons el rol de l’usuari.

---

## ⚙️ Tecnologies utilitzades

- Laravel 10 + Sanctum
- Vue 3 + Vite + Axios
- Laravel Blade (frontend)
- Tailwind CSS (estils i disseny UX/UI)
- L5 Swagger (documentació API)
- PHP 8.2 + XAMPP (entorn local)

---

## 🛠️ Instal·lació i posada en marxa

### 1️⃣ Clona el projecte:
```bash
cd gestor-productes
```

### 2️⃣ Instal·la les dependències de Laravel:
```bash
composer install
```

### 3️⃣ Crea el fitxer `.env` i genera la clau:
```bash
cp .env.example .env
php artisan key:generate
```

### 4️⃣ Configura la base de dades al `.env` i després:
```bash
php artisan migrate --seed
```

> 🟢 El seeder crea dos usuaris:
> - Admin: `admin@example.com` / password: `password`
> - User: `user@example.com` / password: `password`

### 5️⃣ Instal·la les dependències de Vue + Vite:
```bash
npm install
npm run dev
```

### 6️⃣ Arrenca el servidor Laravel:
```bash
php artisan serve
```

---

## 🔐 Credencials de prova

| Rol       | Email                | Password   |
|-----------|----------------------|------------|
| **Admin** | admin@example.com     | password   |
| **User**  | user@example.com      | password   |

---

## 📑 Documentació de l’API (Swagger)

L’API està documentada mitjançant Swagger. Pots accedir a la documentació aquí:

```
http://localhost:8000/api/documentation
```

---

## 🖥️ Funcionament de l’aplicació

### 🔸 Login:
- Formulari de login creat amb Blade + Vue.js.
- Validació de credencials contra l’API.

### 🔸 Gestió de productes:
| Acció                    | Admin | User  |
|--------------------------|-------|-------|
| Veure productes          | ✅    | ✅    |
| Ordenar per Nom / Preu   | ✅    | ✅    |
| Crear productes          | ✅    | ❌    |
| Editar productes         | ✅    | ❌    |
| Eliminar productes       | ✅    | ❌    |

### 🔸 Experiència d’usuari:
- Loader mentre es carreguen els productes.
- Missatges d’èxit i error accessibles i amb animació.
- Responsive i disseny modern (UX/UI premium).

---

## 💎 Separació clara entre Backend i Frontend

- L’API RESTful està separada de la interfície de l’usuari.
- El frontend consumeix l’API mitjançant Axios i utilitza token via Laravel Sanctum.

---

## 📂 Lliurament

Aquest projecte es lliura segons les instruccions del professor, per mitjà del Clickedu de l’assignatura.

---

## 🙌 Gràcies!

Si tens cap dubte, estic a disposició per explicar qualsevol part del projecte. 😊
