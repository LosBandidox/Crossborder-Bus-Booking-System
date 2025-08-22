# 🚌 Cross-Border Bus Booking System  

## 📌 Project Description  
The **Cross-Border Bus Booking System** is a web-based application that automates bus ticket reservations for both local and international travelers. It allows customers to book seats online, make payments securely, and view their tickets without visiting a booking office. The system supports different user roles including **Customers, Drivers, Technicians, and Administrators**, each with specific features for managing bookings, schedules, buses, and maintenance.  

---

## ✨ Features  
- 🔑 **User Authentication** (Secure login with password hashing & verification)  
- 🎟 **Seat Selection** (Real-time bus seat map with availability updates)  
- 💳 **Payments** (Supports methods such as Mobile Money and cash)  
- 📅 **Booking Management** (Create, cancel, or update bookings)  
- 🚌 **Bus & Route Management** (Admins can add and manage routes & buses)  
- 👨‍💼 **Role-Based Access Control (RBAC)** (Customers, Drivers, Technicians, Admins)  
- 🛠 **Maintenance Tracking** (Technicians log bus service and repairs)  
- 📊 **Reports & Analytics** (Bookings, payments, revenue, route usage)  

---

## 🛠 Technologies Used  
- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** PHP  
- **Database:** MySQL  
- **Other Tools:** Git, Apache Server  

---
### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (e.g., Apache or Nginx)
- Composer (for dependency management)

## 🚀 Installation Guide  

1. **Clone the repository:**  
   ```bash
   git clone https://github.com/LosBandidox/Crossborder-Bus-Booking-System.git
   
2. **Move files into your web server root**  
   - For XAMPP: place files in the `htdocs` folder.  
   - For Apache: place files in `/var/www/html`.  

3. **Create the database**
   - Create a MySQL database named `internationalbusbookingsystem`
   - Import the SQL file provided (`InternationalBusBookingSystem.sql`) into MySQL.  
   - Example:  
     ```bash
     mysql -u root -p < InternationalBusBookingSystem.sql
     ```  

5. **Configure database connection**  
   - Edit `databaseconnection.php` and `db_connection.php` with your database credentials.  

6. **Run the application**
   - Start the server
   - Open a browser and go to:  
     ```
     http://localhost/Crossborder-Bus-Booking-System

---

## 👥 User Roles  

- **Customer:** Register, search buses, book tickets, make payments, view/cancel bookings.  
- **Driver:** View assigned trips, passenger list, and update profile.  
- **Technician:** Log bus maintenance, view history, update profile.  
- **Admin:** Manage routes, buses, schedules, bookings, payments, staff, and generate reports.  

---

## 🔐 Security Features  

- Password Hashing & Verification (`password_hash`, `password_verify`)  
- Role-Based Access Control (RBAC)  
- Session Management  
- Activity Logging  
- Input Validation  

---

## 📊 Sample Reports  

- Booking Report  
- Payment Report  
- Route Performance  
- Bus Utilization  
- Maintenance Report  

---

## 📄 License  

This project was developed as a **Final Year Project** for academic purposes.  
You are free to use, modify, and improve it for educational and personal use.  

---

⚡ Developed by **David Kimathi Muthui – 2025**

