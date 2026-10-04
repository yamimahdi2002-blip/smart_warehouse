# 📦 Smart Warehouse Management System

A robust and scalable **Multi-Warehouse Management System** built with **Laravel**. This application helps businesses manage multiple warehouses, track inventory levels in real-time, handle product categories, and streamline stock movements efficiently.

---

## ✨ Features

- 🏢 **Multi-Warehouse Support:** Manage stock across multiple warehouse locations seamlessly.
- 📦 **Inventory & Product Management:** Track products, categories, stock limits, and SKUs.
- 🔄 **Stock Movement Tracking:** Monitor inbound and outbound inventory transfers.
- 📊 **Dashboard & Analytics:** Clear overview of current stock levels and warehouse performance.
- 🔐 **Role-Based Access Control:** Differentiated access for administrators and warehouse managers.

---

## 🛠️ Tech Stack

- **Framework:** Laravel
- **Frontend:** Blade Templates
- **Database:** MySQL / MariaDB
- **Language:** PHP

---

## 🚀 Getting Started

Follow these steps to run the project locally on your machine:

1. **Clone the repository:**
   git clone https://github.com/yamimahdi2002-blip/smart_warehouse.git
   cd smart_warehouse

2. **Install PHP dependencies:**
   composer install

3. **Environment Setup:**
   cp .env.example .env
   php artisan key:generate

4. **Configure Database:**
   Update your `.env` file with your database credentials.

5. **Run Migrations & Start Server:**
   php artisan migrate
   php artisan serve
