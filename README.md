# Lab Inventory System (LCIS)

A comprehensive **Laboratory Computer Inventory Management System** built with **Laravel**, designed to track and manage laboratory equipment efficiently.

---

## 📸 Screenshots

### Dashboard Overview
![alt text](https://github.com/MIAbidin/lab-inventory-system/blob/main/public/img/dashboard.png?raw=true)
*Main dashboard showing inventory statistics and distribution charts*

### Category Management
*Category management interface with item counts*

### Inventory Listing
*Complete inventory listing with filtering and search capabilities*

### Reports Module
*Comprehensive reporting system with export functionality*

### PDF Export Sample
*Sample PDF report with detailed inventory information*

---

## ✨ Features

### Core Functionality
- **Inventory Management**: Complete CRUD operations for laboratory equipment  
- **Category Organization**: Hierarchical categorization system  
- **Automatic Code Generation**: Smart inventory code generation (`MMDDYY-CategoryID-ItemID` format)  
- **Status Tracking**: Real-time status monitoring *(Available, In Use, Maintenance, Disposed)*  
- **Condition Assessment**: Equipment condition tracking *(Good, Need Repair, Broken)*  

### Advanced Features
- **Smart Search**: Multi-field search across names, codes, brands, models, and serial numbers  
- **Advanced Filtering**: Filter by category, condition, status, location, and date ranges  
- **Image Management**: Photo upload and storage for equipment  
- **History Tracking**: Complete audit trail for all item changes  
- **Report Generation**: Comprehensive PDF and Excel export capabilities  
- **Dashboard Analytics**: Visual statistics with charts and graphs  
- **Responsive Design**: Mobile-friendly interface with collapsible sidebar  

### Technical Features
- **Modern UI**: Bootstrap 5 with custom gradient design  
- **Chart Visualization**: Chart.js integration for data visualization  
- **File Handling**: Image upload with automatic resizing  
- **Data Export**: PDF (DomPDF) and Excel (Maatwebsite) export  
- **Database Optimization**: Proper indexing and soft deletes  
- **Authentication**: Laravel Auth with profile management  

---

## ⚙️ System Requirements

- PHP >= 8.1  
- MySQL >= 8.0 or MariaDB >= 10.3  
- Composer  
- Node.js & NPM (for asset compilation)  
- Web server (Apache/Nginx)  

---

## 🚀 Installation

1. **Clone Repository**
   ```bash
    git clone https://github.com/user/lab-inventory-system.git
    cd lab-inventory-system

2. **Install Dependencies**
   ```bash
    # Install PHP dependencies
    composer install

    # Install Node.js dependencies
    npm install && npm run build

2. **Environment Configuration**
   ```bash
    # Copy environment file
    cp .env.example .env

    # Generate application key
    php artisan key:generate

4. **Database Setup**

    Configure your database in `.env`:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=lab_inventory
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```
    Run migrations:
    ```bash
    php artisan migrate
    ```
5. **Storage Setup**
    ```bash
    # Create storage symbolic link
    php artisan storage:link

    # Set proper permissions
    chmod -R 775 storage
    chmod -R 775 bootstrap/cache

6. **Serve Application**
    ```bash
    php artisan serve
    ```
    Visit http://localhost:8000 in your browser.
