# Diddit

Diddit is a social media web application built using PHP. It allows users to create profiles, upload images, like posts, comment, and receive notifications. The project follows a modular approach with organized classes and AJAX functionalities.

## Features
- User Registration and Login
- Profile Management (with profile and cover images)
- Image Upload and Display
- Like and Comment System
- Notifications for Interactions
- Search Functionality

## File Structure
```
diddit/
├── ajax/                 # AJAX functionalities
├── classes/              # PHP classes for modular components
├── images/               # Default images and user uploads
├── uploads/              # User uploaded images
├── ajax.php               
├── change_image.php       
├── change_profile_image.php
├── comment.php            
├── delete.php             
├── edit.php               
├── header.php             
├── image_view.php         
├── index.php              
├── like.php               
├── likes.php              
├── login.php              
├── logout.php             
├── notifications.php      
├── post.php               
├── post_delete.php        
├── profile.php            
├── search.php             
├── signup.php             
└── user.php               
```

## Installation Guide
1. **Requirements**
   - PHP 7.4 or later
   - MySQL Database
   - Apache or Nginx Server

2. **Database Setup**
   - Create a new MySQL database named `diddit`.
   - Import the provided SQL file (if available) to create the necessary tables.
   - Update the database credentials in `classes/connect.php`.

3. **Configuration**
   - Place the `diddit` folder in your web server's root directory (`htdocs` for XAMPP or `www` for WAMP).
   - Ensure the `uploads/` and `images/` directories have write permissions.

4. **Running the Application**
   - Start the Apache and MySQL services.
   - Open a browser and navigate to `http://localhost/diddit`.

## Usage
- Register a new account or login with existing credentials.
- Create posts, upload images, like posts, and engage with other users.
- Manage profile settings and receive notifications.

## Contributing
Feel free to contribute by opening issues or submitting pull requests.

## License
This project is licensed under the MIT License.

