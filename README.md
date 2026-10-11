# Daycare Management System

A PHP and MySQL web application for managing a daycare centre. Staff can track children, attendance, meals, activities, invoices, and notifications. Parents can view their child’s daily updates, fees, and messages.

## Author

**Usman Younis**  
Email: [usmanyounis7475@gmail.com](mailto:usmanyounis7475@gmail.com)

## Features

### Roles

| Role | Access |
|------|--------|
| **Parent** | Profile, children, attendance, meals, activities, fees, notifications, chat |
| **Admin (Daycare)** | Full staff dashboard and account management |
| **Manager** | Staff dashboard |
| **Carer** | Staff dashboard |

### Staff (Admin / Manager / Carer)

- Daycare dashboard
- Child records
- Attendance reports
- Daily activity reports (playtime, learning, meals, naps, photos)
- Meal schedule (breakfast, lunch, snack, dinner)
- Invoice generation based on attendance and daily fee
- Notifications (event, holiday, emergency)
- Chat with parents
- Create and manage staff accounts (carer / manager)

### Parents

- Sign up and manage profile (including emergency contact)
- Add children
- View attendance, meal schedule, and daily activities
- View invoices and payments
- Receive notifications
- Chat with daycare staff

## Tech Stack

- PHP 8+
- MySQL / MariaDB
- Apache (XAMPP)
- Bootstrap 4
- Custom CSS (`styles.css`)

## Requirements

- [XAMPP](https://www.apachefriends.org/) (or Apache + PHP + MySQL)
- PHP with `mysqli` enabled
- A modern web browser

## Installation

1. Clone or copy the project into your XAMPP `htdocs` folder:

   ```bash
   git clone https://github.com/YOUR_USERNAME/daycare2.git
   ```

   Example local path:

   ```text
   C:\xampp\htdocs\daycare2
   ```

2. Start **Apache** and **MySQL** from the XAMPP Control Panel.

3. Create the database:
   - Open [phpMyAdmin](http://localhost/phpmyadmin)
   - Import `Database sql file/daycare_system.sql`
   - This creates the `daycare_system` database, tables, and sample data

4. Database connection is set in `db_connection.php`:

   ```php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "daycare_system";
   ```

   Change these values if your MySQL user or password is different.

5. Open the app in the browser:

   ```text
   http://localhost/daycare2/
   ```

## Usage

- **Sign in:** `index.php` — choose role (Parent, Admin, Carer, or Manager), then enter email and password.
- **Parent sign up:** `signup.php` — new parent accounts are created here.
- **Staff accounts:** an admin can add carer and manager users from **Manage Account**.

After login:

- Parents are redirected to `parent_profile.php`
- Admin, manager, and carer users are redirected to `daycare_dashboard.php`

## Project Structure

```text
daycare2/
├── index.php                 # Sign in
├── signup.php                # Parent registration
├── logout.php
├── db_connection.php         # MySQL connection
├── styles.css
├── Database sql file/
│   └── daycare_system.sql    # Database dump
│
├── daycare_dashboard.php     # Staff home
├── daycare_child.php         # Staff child list
├── attendance_report.php
├── activity_report.php
├── meals_schedule.php
├── generate_invoices.php
├── manage_account.php        # Add/delete staff users
├── daycare_chat.php
├── notifications.php
├── sidebar.php
│
├── parent_profile.php
├── add_child.php
├── child_attendence.php
├── child_activity.php
├── meal.php
├── child_payment.php
├── child_notifications.php
├── parent_chat.php
└── navbar.php
```

## Database Tables

- `users` — accounts and roles
- `parents` — extra parent profile details
- `daycare_children` — enrolled children
- `attendance` — check-in records
- `child_activities` — daily activity logs
- `meals_schedule` — meal plans
- `invoices` — monthly fees
- `payments` — payment history
- `notifications` — centre announcements
- `messages` — parent–staff chat

## License

This project is for academic / personal use. Contact the author if you want to reuse or extend it.

---

Developed by **Usman Younis** · [usmanyounis7475@gmail.com](mailto:usmanyounis7475@gmail.com)
