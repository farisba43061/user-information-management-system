# User Information Management System

A simple web application built using **HTML, CSS, PHP, and MySQL** that allows users to add records to a database, display them in a table, and toggle each user's status between **0** and **1**.

## 🌐 Live Website

**Website:** http://datastorage.2kool4u.net/

---

## Screenshot

![User Information Management System](<img width="1905" height="906" alt="Screenshot 2026-07-21 011900" src="https://github.com/user-attachments/assets/9818e95e-e2dc-4ea3-ab90-a899393ffa8b" />
)

> **Note:** Replace `image.png` with the name of your screenshot if it is different.

---

## Features

- Add new users by entering their **Name** and **Age**.
- Store user information in a **MySQL** database.
- Display all records in a responsive table.
- Toggle the **Status** value between **0** and **1**.
- Automatically capitalize user names (e.g., `john doe` → `John Doe`).
- Modern and responsive user interface.

---

## Technologies Used

- HTML5
- CSS3
- PHP
- MySQL
- phpMyAdmin
- InfinityFree Hosting

---

## Database Structure

**Table Name:** `user`

| Column | Type | Description |
|--------|------|-------------|
| ID | INT (Primary Key, Auto Increment) | User ID |
| Name | VARCHAR(255) | User Name |
| Age | INT | User Age |
| Status | TINYINT(1) | Status value (0 or 1) |

---

## Project Structure

```text
htdocs/
│── index.php
```

---

## How It Works

1. Enter the user's **Name** and **Age**.
2. Click the **Submit** button.
3. The data is stored in the MySQL database.
4. All records are displayed in the table.
5. Click the **Toggle** button to change the status between **0** and **1**.
6. The page refreshes automatically after each update.

---

## Installation

1. Create a MySQL database.
2. Create a table named **user** with the required columns.
3. Update the database connection information inside `index.php`.
4. Upload `index.php` to the `htdocs` directory on InfinityFree.
5. Open the website using the link above.

---

## Future Improvements

- Edit existing users.
- Delete users.
- Search and filter records.
- User authentication system.
- Display **Active** and **Inactive** instead of **0** and **1**.

---

## 👤 Author
**Faris Bahussain**

## 📚 Track
Web & App Development Track

