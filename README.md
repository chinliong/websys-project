# Little Haven Shoppe  

Little Haven Shoppe is a full-featured e-commerce web application built with **PHP** and **MySQL**. It serves as a community-driven marketplace where users can buy and sell products easily and securely.  

---

## Features  

### User Features  
- **Registration & Login**: Email-based signup with OTP, secure login with reCAPTCHA, password hashing, and session management.  
- **Product Browsing**: Search with category filters, product detail pages, and responsive design for mobile and desktop.  
- **Shopping Cart & Checkout**: Add/remove items, responsive cart, digital wallet payments, and secure checkout process.  
- **Profile Management**: Edit profile, view transactions, manage digital wallet, and delete account with confirmation.  

### Seller Features  
- Create, edit, and delete product listings with images.  
- Category-based product organization.  
- View sales analytics with charts.  

### Admin Features  
- Manage users, products, and transactions.  
- Role-based access control for admins.  

### Support Features  
- FAQ section, email support with PHPMailer, live chat via Tawk.to, and location finder using Google Maps.  

---

## Technical Stack  

**Backend:** PHP 7.4+, MySQL, PHPMailer, phpdotenv  
**Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript/jQuery, Chart.js, Font Awesome  
**Integrations:** PayPal SDK, Google reCAPTCHA, Google Maps API, Tawk.to  

---

## Database Schema  

Main tables:  
- `user_table` – Users and wallet info  
- `product_table` – Product listings  
- `product_category` – Categories  
- `cart_table` – Shopping cart items  
- `transaction_table` – Transaction history  

---

## Installation  

1. Clone the repository.  
2. Install PHP dependencies with Composer.  
3. Create a MySQL database and import the schema.  
4. Update database credentials in `/var/www/private/db-config.ini`.  
5. Create a `.env` file for environment variables.  
6. Configure API keys for PayPal, Google reCAPTCHA, Maps, and Tawk.to.  
7. Set correct file permissions.  

---

## Security Features  

- Password hashing with `password_hash()`  
- SQL injection prevention with prepared statements  
- XSS protection using `htmlspecialchars()`  
- Secure session-based authentication  
- reCAPTCHA for spam protection  

---

## License  

This project is part of an academic assignment. All rights reserved.  

---

## Contributors  

For any questions or suggestions, please contact the development team.  
