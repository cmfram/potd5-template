## 🧩 Prerequisites
Each teammate needs:
- **XAMPP** (download at [https://www.apachefriends.org](https://www.apachefriends.org))


### Clone the repo
Open **Git Bash** or your terminal and run:

```bash
cd "C:\xampp\htdocs\potd5"
git clone https://github.com/USERNAME/potd5-template.git template

Replace USERNAME with your username

### Start XAMPP services
Open XAMPP control panel and start Apache and MySQL

### Create the database
1. Go to http://localhost/phpmyadmin
2. Click Databases → Create new database
Name it: mvn5yd_d
3. Import the schema:
Click the new database in the left sidebar
Go to the Import tab
Choose the file: maintenance_system.sql (found in the repo)

### Configure database connection
Open the file C:\xampp\htdocs\potd5\template\connect-db.php

### Run the app
Visit this http://localhost/potd5/template/request.php
You should see maintence request form