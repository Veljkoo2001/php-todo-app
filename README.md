![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

✅ PHP To-Do App – Web Application

This project is a simple web-based To-Do application built using PHP, HTML, CSS, and SQL. The application allows users to create, read, update, and delete (CRUD) tasks, helping them manage daily activities and organize their workflow.
The goal of this project is to demonstrate server-side scripting, form handling, database interactions, and frontend integration using PHP, while keeping the code clean and maintainable.

## 🔍 Features

➕ Add Tasks – Users can create new tasks by entering a title and optional description.
👁️ View Tasks – Displays all tasks in a structured table or list format.
✏️ Edit Tasks – Users can update task information (title, description, or status).
❌ Delete Tasks – Users can remove tasks they have completed or no longer need.
✅ Mark as Completed – Optionally, tasks can be marked as done to distinguish pending and completed items.

⚙️ How It Works (Program Logic)

1. Frontend Forms
Users interact with HTML forms to input and submit task data. Forms include validation to ensure required fields are completed.

2. Server-Side Processing (PHP)
PHP scripts receive form submissions via POST or GET requests. The server processes these requests and executes appropriate CRUD operations on the database.

3. Database Interaction (SQL/MySQL)

- Tasks are stored in a MySQL database.
- TThe application uses MySQL with prepared statements to ensure secure and structured database interaction.

4. Dynamic Content Rendering
PHP dynamically generates HTML pages with current task data, allowing users to see updates immediately after any action (add/edit/delete).

🛠 Technologies Used

PHP – server-side programming language
HTML & CSS – structure and styling of the application
JavaScript – enhancing frontend interactivity
MySQL – relational database for storing tasks
XAMPP – local development server environment

🎯 Purpose of the Project

This project was built to:
Practice backend development with PHP
Learn database connectivity and CRUD operations
Implement server-side form handling and validation
Understand dynamic content rendering with PHP
Demonstrate a complete web application workflow in a portfolio

🚀 Possible Improvements & Future Enhancements

Add user authentication to allow multiple users to have private task lists
Implement categories or tags for tasks
Add due dates and reminders for tasks
Use JavaScript/AJAX for a smoother, asynchronous user experience
Store data in SQLite or PostgreSQL for alternative database options
Deploy the app online using shared hosting or cloud servers

🖥 Installation & Setup

1. Clone the repository:
   git clone https://github.com/Veljkoo2001/php-todo-app
2. Move the project folder to your local server directory:
   - XAMPP: htdocs/
   - WAMP: www/
   - LAMP: /var/www/html/
3. Create a MySQL database (e.g., todo_app).
4. Import the provided SQL file (if available) or create the required tables manually.
5. Update database credentials in config file:
   - host
   - username
   - password
   - database name
6. Start Apache and MySQL from XAMPP/WAMP.
7. Open in browser:
   http://localhost/php-todo-app/

🗄 Database Structure

Table: tasks
- id (INT, Primary Key, Auto Increment)
- task_text (VARCHAR)
- is_completed (TINYINT, NULL)
- created_at (TIMESTAMP)
- user_id (INT, Index, NULL)
- deadline (DATE, NULL)
- priority ( ENUM('low','medium','high') )

Table: users
- id (INT, Primary key, Auto Increment)
- username (VARCHAR, Index)
- email (VARCHAR, Index)
- password_hash (VARCHAR)
- created_at (TIMESTAMP)

👨‍💻 The author

**Veljkoo2001**
- GitHub: [@Veljkoo2001](https://github.com/Veljkoo2001)

📝 License
This project is licensed under [MIT licencom](LICENSE).