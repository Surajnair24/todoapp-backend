Tech Stack
Frontend
React.js for building the UI.
Axios for API calls.
React Router for navigation.
Backend
PHP for handling server-side logic.
MySQL for database operations.
RESTful APIs for communication between the frontend and backend.
(used XAMPP (Apache nad mysql) for development in localhost)

Frontend:
Created 4 pages for the user interface :
1. Sign Up Page
2. login page
3. Dashboard for displaying projects and creating new project
4. project details 

Database:
created 3 tables under the todoapp database 
1. for storing sign up details to check while logging in.
2. for storing projects
3. for storing projects details entered by user

Frontend Features
React-based user interface with routing for different pages (Landing Page, Sign Up, Login, Dashboard, and Project View).
Responsive design for a better user experience.
Form validations for user inputs.

Backend Features
PHP-based REST APIs.
MySQL database for storing user, project, and todo data.
CRUD operations for users, projects, and todos.
Authentication and session management.


sql for creating sign_up_details table in todoapp DB

CREATE TABLE `sign_up_details` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
)

sql for creating projects table in todoapp DB

CREATE TABLE `projects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL,
    `project_title` VARCHAR(255) NOT NULL,
)

sql for creating todos table in todoapp DB

CREATE TABLE `todos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` INT NOT NULL,
    `project_name` INT NOT NULL,
    `description` TEXT NOT NULL,
    `completed` TINYINT(1) DEFAULT 0,
    `date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
)
