# 🚗 RentRide – Vehicle Rental Website

**RentRide** is a web-based vehicle rental management system developed using **PHP and MySQL**. The application allows users to browse available cars and bikes, filter vehicles by city and brand, view vehicle details, and make rental bookings.

The project also includes an **Admin Dashboard** for managing vehicles, cities, brands, users, and bookings.

---

## 📌 Project Overview

RentRide provides a simple platform for customers to find and rent vehicles online.

Users can:

* 🔍 Search vehicles by city and brand
* 🚗 Browse available cars and bikes
* 📄 View detailed vehicle information
* 📅 Select pickup and return dates
* 📍 Select pickup location
* 💰 Calculate rental cost based on rental duration
* 🔐 Create an account and log in
* 📋 View booking history
* 🚪 Logout securely

Administrators can manage the rental system through the admin section.

---

## ✨ Features

### 👤 User Features

* User Registration
* User Login & Logout
* Vehicle Search
* City-based Filtering
* Brand-based Filtering
* Vehicle Details
* Online Vehicle Booking
* Pickup & Return Date Selection
* Rental Duration Calculation
* Total Price Calculation
* Booking History

### 🛠️ Admin Features

* Admin Login
* Admin Dashboard
* Vehicle Management
* Brand Management
* City Management
* Booking Management
* User Management
* Booking & Revenue Information
* Vehicle Image Upload

---

## 🧑‍💻 Technology Stack

| Technology       | Purpose                                 |
| ---------------- | --------------------------------------- |
| **PHP**          | Backend development & server-side logic |
| **MySQL**        | Database management                     |
| **HTML5**        | Website structure                       |
| **CSS3**         | Styling                                 |
| **JavaScript**   | Client-side functionality               |
| **Bootstrap 5**  | Responsive UI design                    |
| **XAMPP**        | Local development server                |
| **Git & GitHub** | Version control                         |

The current project uses Bootstrap 5 through its CDN and PHP/MySQL for the application and database functionality.

---

## 📂 Project Structure

```text
rentalWebsite/
│
├── Bikes/
│
├── uploads/
│
├── admin.php
├── admin_brand.php
├── admin_city.php
├── adminlogin.php
├── adminlogout.php
│
├── booking.php
├── booking_history.php
│
├── config.php
│
├── dashboard.php
├── dashboard2.php
│
├── details.php
├── vehicle_details.php
├── vehicles.php
├── vehicle_admin.php
│
├── index.php
│
├── login.html
├── login.php
├── loginpage.php
│
├── signup.html
├── signup.php
│
├── thankyou.php
├── userlogout.php
│
└── README.md
```

The repository currently contains the main customer pages, booking pages, authentication pages, vehicle-management files, admin files, and an `uploads` directory.

---

## 🔄 User Workflow

```text
        ┌───────────────┐
        │   Home Page   │
        │   index.php   │
        └───────┬───────┘
                │
                ▼
       ┌──────────────────┐
       │ Search / Filter  │
       │ City + Brand     │
       └────────┬─────────┘
                │
                ▼
       ┌──────────────────┐
       │ Vehicle Listing  │
       └────────┬─────────┘
                │
          ┌─────┴─────┐
          ▼           ▼
   ┌────────────┐ ┌────────────┐
   │   Details  │ │  Book Now  │
   └────────────┘ └──────┬─────┘
                         │
                         ▼
                 ┌───────────────┐
                 │ User Login    │
                 └───────┬───────┘
                         │
                         ▼
                 ┌───────────────┐
                 │ Booking Form  │
                 └───────┬───────┘
                         │
                         ▼
                 ┌───────────────┐
                 │ Booking Saved │
                 └───────┬───────┘
                         │
                         ▼
                 ┌───────────────┐
                 │ Booking       │
                 │ History       │
                 └───────────────┘
```

The home page supports city/brand filtering and provides separate **View** and **Book Now** actions for vehicles.

---

## 🗄️ Database

The application uses **MySQL** as the backend database.

The system works with data related to:

* Users
* Vehicles
* Brands
* Cities
* Bookings
* Admin management

The PHP application connects to MySQL through `config.php`.

> **Note:** The SQL database file is not currently included in this repository. To run the project locally, you need to create/import the required MySQL database and configure the database connection in `config.php`.

---

## ⚙️ Installation & Setup

### 1. Install XAMPP

Download and install **XAMPP** with:

* Apache
* MySQL
* PHP

### 2. Clone the Repository

```bash
git clone https://github.com/pushpendrakumar0205-design/rentalWebsite.git
```

### 3. Move the Project

Copy the project into:

```text
C:\xampp\htdocs\
```

For example:

```text
C:\xampp\htdocs\rentalWebsite
```

### 4. Start XAMPP

Open XAMPP Control Panel and start:

```text
Apache
MySQL
```

### 5. Configure Database

Open:

```text
config.php
```

Configure your MySQL database connection according to your local XAMPP setup.

### 6. Create Database

Open:

```text
http://localhost/phpmyadmin
```

Create the required database and tables for the project.

### 7. Run the Project

Open your browser and visit:

```text
http://localhost/rentalWebsite/
```

---

## 🔐 Authentication

RentRide provides separate authentication flows for:

### Users

```text
Signup → Login → Browse Vehicles → Book Vehicle → Booking History
```

### Admin

```text
Admin Login → Dashboard → Manage Rental System
```

Session-based authentication is used to manage logged-in users and admin access.

---

## 📊 Admin Dashboard

The admin section provides management functionality for the rental platform.

Administrators can work with:

* Vehicles
* Brands
* Cities
* Users
* Bookings
* Rental information
* Dashboard statistics

The repository contains dedicated dashboard and administration files such as `dashboard.php`, `dashboard2.php`, `vehicle_admin.php`, `admin_brand.php`, and `admin_city.php`.

---

## 🎯 Project Objectives

The main objectives of RentRide are:

* To develop a web-based vehicle rental platform.
* To provide an easy vehicle search and booking process.
* To implement user authentication and session management.
* To manage vehicle rental data using MySQL.
* To provide an administrative management system.
* To practice full-stack web development using PHP and MySQL.

---

## 🚀 Future Enhancements

Possible improvements for future versions:

* 💳 Online Payment Gateway
* 📧 Email Booking Confirmation
* 📱 WhatsApp Booking Notifications
* ⭐ Vehicle Ratings & Reviews
* 🧾 Automatic Booking Invoice
* 📍 Google Maps Integration
* 🔎 Advanced Vehicle Filtering
* 📊 More detailed Admin Analytics
* 🔒 Improved security and input validation
* ☁️ Online deployment

---

## 📸 Screenshots

Add screenshots of your project here to make the repository more attractive.

Example:

```markdown
## 📸 Screenshots

### Home Page
![Home Page](screenshots/home.png)

### Vehicle Details
![Vehicle Details](screenshots/details.png)

### Booking Page
![Booking Page](screenshots/booking.png)

### Admin Dashboard
![Admin Dashboard](screenshots/dashboard.png)
```

---

## 🧠 What I Learned

While developing this project, I worked with:

* PHP server-side programming
* MySQL database operations
* CRUD operations
* User authentication
* Session management
* Form handling
* Database relationships
* Vehicle search and filtering
* Booking management
* Admin dashboard development
* Responsive web design using Bootstrap
* Git and GitHub

---

## 👨‍💻 Author

**Pushpendra Kumar Sahu**

MCA Graduate | Software Developer

### GitHub

[![GitHub](https://img.shields.io/badge/GitHub-Pushpendra-black?style=for-the-badge\&logo=github)](https://github.com/pushpendrakumar0205-design)

---

## ⭐ Support

If you find this project useful or interesting, consider giving the repository a ⭐ on GitHub.

---

## 📄 License

This project is developed for **learning and portfolio purposes**.
