# LOGIN and SIGNUP pages 
# With frontend and backend authentication using PHP + AJAX

## Features
- Login and Signup pages with frontend and backend authentication using PHP + AJAX
- Passwords are hashed and stored in the database
- Passwords are checked using password_verify() function
- Usernames are checked using preg_match() function
- Email addresses are checked using filter_var() function
- Usernames and email addresses are checked for duplicates
- PDO is used for database connection
- Prepared statements are used for database queries
- Database connection is closed after every query

## Installation
- Clone the repository
- Create a database named "php_form" and import the php_form.sql file
- Change the database credentials in the db.php file
- Run the project on localhost
- Default username: `root`
- Default password: `""`
- Default database name: `php_form`
- Default table name: `REGISTER`