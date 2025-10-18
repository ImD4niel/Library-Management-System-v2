# 🚀 Digital Library Management System - Setup Guide

## Quick Start (5 Minutes)

### Step 1: Install XAMPP
1. **Download XAMPP**: Go to https://www.apachefriends.org/download.html
2. **Install**: Run the installer as Administrator
3. **Start Services**: Open XAMPP Control Panel and start:
   - ✅ Apache (Web Server)
   - ✅ MySQL (Database)

### Step 2: Copy Project Files
1. **Copy Project**: Copy the entire `Library-Management-System` folder to:
   - **XAMPP**: `C:\xampp\htdocs\`
   - **WAMP**: `C:\wamp\www\`
   - **MAMP**: `/Applications/MAMP/htdocs/`

### Step 3: Setup Database
1. **Open phpMyAdmin**: Go to http://localhost/phpmyadmin
2. **Create Database**: Click "New" → Name: `library_management`
3. **Import Database**: Click "Import" → Choose `eb_lms.sql` file

### Step 4: Configure Database Connection
Edit `config/database.php`:
```php
private $host = 'localhost';
private $db_name = 'library_management';
private $username = 'root';
private $password = '';
```

### Step 5: Access the System
- **Main Site**: http://localhost/Library-Management-System/
- **Admin Panel**: http://localhost/Library-Management-System/librarian/

## 🔑 Default Login Credentials

### Admin Login
- **URL**: http://localhost/Library-Management-System/librarian/login.php
- **Username**: admin
- **Password**: admin123

### Student Login
- **URL**: http://localhost/Library-Management-System/login.php
- **Username**: [student_number]
- **Password**: [student_password]

## 🛠️ Detailed Setup Instructions

### Prerequisites
- **PHP**: 7.0 or higher
- **MySQL**: 5.7 or higher
- **Web Server**: Apache/Nginx
- **Browser**: Chrome, Firefox, Safari, Edge

### Installation Methods

#### Method 1: XAMPP (Windows/Mac/Linux)
```bash
# Download and install XAMPP
# Start Apache and MySQL
# Copy project to htdocs folder
```

#### Method 2: WAMP (Windows)
```bash
# Download and install WAMP
# Start all services
# Copy project to www folder
```

#### Method 3: MAMP (Mac)
```bash
# Download and install MAMP
# Start Apache and MySQL
# Copy project to htdocs folder
```

### Database Setup

#### Option 1: Using phpMyAdmin
1. Open http://localhost/phpmyadmin
2. Create new database: `library_management`
3. Import the SQL file: `eb_lms.sql`

#### Option 2: Using Command Line
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE library_management;"

# Import database
mysql -u root -p library_management < eb_lms.sql
```

### Configuration Files

#### Database Configuration (`config/database.php`)
```php
private $host = 'localhost';
private $db_name = 'library_management';
private $username = 'root';
private $password = ''; // Leave empty for XAMPP
```

#### Email Configuration (`includes/email.php`)
```php
$this->smtp_host = 'smtp.gmail.com';
$this->smtp_username = 'your-email@gmail.com';
$this->smtp_password = 'your-app-password';
```

## 🎯 First Time Setup

### 1. Access the System
- Open browser and go to: http://localhost/Library-Management-System/
- You should see the library homepage

### 2. Login as Admin
- Go to: http://localhost/Library-Management-System/librarian/login.php
- Use credentials: admin / admin123

### 3. Change Default Password
- Go to Settings → Change Password
- Set a strong password for security

### 4. Add Sample Data
- Add some books through the admin panel
- Register some students/members
- Test the borrowing system

## 🔧 Troubleshooting

### Common Issues

#### 1. "Database Connection Failed"
**Solution**: Check database credentials in `config/database.php`

#### 2. "Page Not Found" (404 Error)
**Solution**: 
- Ensure Apache is running
- Check file permissions
- Verify project is in correct directory

#### 3. "Permission Denied"
**Solution**: 
```bash
# Set proper permissions
chmod 755 /path/to/project/
chmod 644 /path/to/project/.htaccess
```

#### 4. "PHP Errors"
**Solution**: 
- Check PHP error logs
- Ensure PHP version is 7.0+
- Verify all required extensions are installed

### Port Conflicts

#### Apache Port 80 in Use
**Solution**: 
- Change Apache port to 8080 in XAMPP
- Access via: http://localhost:8080/Library-Management-System/

#### MySQL Port 3306 in Use
**Solution**: 
- Change MySQL port in XAMPP settings
- Update database configuration

## 📱 PWA Features

### Install as App
1. Open the system in Chrome/Edge
2. Look for "Install" button in address bar
3. Click to install as desktop app

### Offline Functionality
- System works offline after first visit
- Cached data available without internet
- Automatic sync when connection restored

## 🔒 Security Setup

### Change Default Credentials
1. **Admin Password**: Change immediately after first login
2. **Database Password**: Set strong MySQL password
3. **File Permissions**: Set appropriate file permissions

### Production Deployment
1. **HTTPS**: Use SSL certificate
2. **Database**: Use strong passwords
3. **Server**: Configure security headers
4. **Backup**: Regular database backups

## 📊 System Features

### For Librarians
- ✅ Book Management (Add, Edit, Delete)
- ✅ Member Management
- ✅ Transaction Processing
- ✅ Analytics Dashboard
- ✅ Email Notifications
- ✅ QR Code Generation

### For Students
- ✅ Book Search
- ✅ Account Management
- ✅ Borrowing History
- ✅ Due Date Notifications

### Modern Features
- ✅ Progressive Web App (PWA)
- ✅ Real-time Search
- ✅ Dark Mode
- ✅ Mobile Responsive
- ✅ Offline Support

## 🆘 Support

### Getting Help
1. **Check Logs**: Look at PHP error logs
2. **Browser Console**: Check for JavaScript errors
3. **Database**: Verify MySQL is running
4. **Permissions**: Check file permissions

### Common Commands
```bash
# Check PHP version
php --version

# Check MySQL status
mysql --version

# Test database connection
mysql -u root -p -e "SHOW DATABASES;"
```

## 🎉 Success!

Once everything is running, you should see:
- ✅ Library homepage loads
- ✅ Admin login works
- ✅ Database connection successful
- ✅ All features functional

**Your Digital Library Management System is ready to use!**

---

**Need Help?** Check the troubleshooting section above or refer to the PROJECT_REPORT.md for detailed documentation.
