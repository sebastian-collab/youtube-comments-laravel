# 🎥 Laravel 12 YT Comments API dedmo

A simple Laravel 12 API for managing yt video comments, including replies, likes, and dislikes.  

## 🛠 Tech Stack  
- **Laravel**: 12  
- **PHP**: 8.2  
- **Database**: MySQL  

---

## 📂 Extra Files  
Inside the **`extra/`** folder, you’ll find:  
✅ **SQL File** – Just import this into your database, and you're good to go!  
✅ **Postman Collection** – Makes API testing easy.  
✅ **ENV File** – No need to set up manually, just use this.  

---

## 🚀 How to Run  

### 1️⃣ Install Dependencies  
Make sure you have **PHP 8.2**, **Composer**, and **MySQL** installed. Then run:  
```sh
composer install
```

### 2️⃣ Import Database  
Import the SQL file from the `extra/` folder into your MySQL database.  

### 3️⃣ Run Migrations (if needed)  
If you want to create tables manually, run:  
```sh
php artisan migrate
```

### 4️⃣ Start the Server  
```sh
php artisan serve
```
Your API will be running at:  
👉 **http://localhost:8000/api/video-comments**  

---

## 📌 Testing the API  
Use **Postman** (collection provided in `extra/` folder).  

---
