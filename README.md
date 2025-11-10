# ⚡ Espvatio

**Espvatio** es una plataforma moderna de monitoreo de energía y consumo eléctrico.  
Permite a usuarios y organizaciones visualizar, analizar y gestionar el uso energético de sus dispositivos conectados (IoT) en tiempo real.

Diseñada con **Laravel 12**, **Livewire 3** y **Filament 3**, Espvatio combina la potencia del ecosistema Laravel con una experiencia de usuario fluida y reactiva.  
Los dispositivos (como **ESP8266** o **ESP32**) se comunican con el backend mediante **MQTT**, lo que permite una integración directa con sensores y medidores inteligentes.

---

## 🚀 Características Principales

- **Monitoreo en tiempo real:** Recibe y procesa mediciones eléctricas desde dispositivos IoT mediante MQTT.  
- **Dashboard global:** Visualiza métricas agregadas como consumo total (kWh), costo estimado y número de dispositivos.  
- **Gestión de dispositivos:** Añade, edita o elimina dispositivos fácilmente desde la interfaz.  
- **Vista detallada de dispositivos:** Accede al historial y métricas individuales de cada equipo.  
- **Autenticación y equipos:** Integración completa con **Laravel Jetstream** para usuarios y equipos.  
- **Seguridad IoT:** Uso de **Laravel Sanctum** y tokens temporales de aprovisionamiento para registro seguro de dispositivos.  
- **Panel de administración avanzado:** Construido sobre **Filament 3**, permite la administración centralizada de usuarios, dispositivos y datos.

---

## 🧩 Arquitectura General

```text
┌──────────────────────┐
│   Dispositivos IoT   │  → Envían mediciones por MQTT
│ (ESP8266 / ESP32)    │
└─────────┬────────────┘
          │
          ▼
┌──────────────────────┐
│      Broker MQTT     │
└─────────┬────────────┘
          │
          ▼
┌──────────────────────────────┐
│       Backend (Laravel 12)   │
│  - Jetstream (auth)          │
│  - Sanctum (API tokens)      │
│  - php-mqtt/client           │
│  - Filament (admin panel)    │
│  - Livewire (UI reactiva)    │
└─────────┬────────────────────┘
          │
          ▼
┌──────────────────────────────┐
│        Interfaz Web          │
│     (Livewire + Vite)        │
└──────────────────────────────┘

```



## 🔧 Endpoints Principales

### **Rutas Web (Autenticadas)**

| Método | Ruta | Descripción |
|--------|------|-------------|
| `GET` | `/dashboard` | Muestra métricas globales del usuario. |
| `GET` | `/devices` | Lista y gestiona dispositivos registrados. |
| `GET` | `/devices/{device}` | Muestra métricas y datos de un dispositivo específico. |

### **Rutas de API (IoT & Aprovisionamiento)**

| Método | Ruta | Controlador | Descripción |
|--------|------|--------------|--------------|
| `POST` | `/provision` | `ProvisionController` | Registro público de nuevos dispositivos IoT. |
| `POST` | `/provisioning-tokens` | `ProvisioningTokenController` | Genera tokens de aprovisionamiento (requiere autenticación). |

---

## 🛠️ Instalación y Configuración

### **Requisitos Previos**
- PHP >= 8.2  
- Composer  
- Node.js & NPM  
- MySQL o PostgreSQL  
- Broker MQTT (ej. Mosquitto o EMQX)

### **Instalación Rápida**

```bash
# Clonar el repositorio
git clone https://github.com/tuusuario/espvatio.git
cd espvatio

# Instalar dependencias PHP
composer install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env y luego:
php artisan migrate --force

# Instalar dependencias frontend
npm install
npm run build
