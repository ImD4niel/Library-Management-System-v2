# 📚 Digital Library Management System

[![PHP](https://img.shields.io/badge/PHP-7.0+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)
[![PWA](https://img.shields.io/badge/PWA-Ready-green.svg)](https://web.dev/progressive-web-apps/)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

A modern, secure, and feature-rich web-based library management system built with PHP, MySQL, and Bootstrap 5. Features Progressive Web App (PWA) capabilities, real-time search, QR code integration, and comprehensive analytics.

## ✨ Features

### 🔐 Security & Performance
- **Modern Security**: PDO prepared statements, password hashing, CSRF protection
- **Progressive Web App**: Installable, offline-capable, mobile-ready
- **Real-time Search**: AJAX live search with instant results
- **Responsive Design**: Works perfectly on all devices

### 📊 Advanced Features
- **Analytics Dashboard**: Comprehensive statistics and reporting
- **QR Code Integration**: Generate and scan QR codes for books/members
- **Email Notifications**: Automated reminders and alerts
- **Dark Mode**: Toggle between light and dark themes
- **Accessibility**: WCAG compliant with screen reader support

### 🎯 Core Functionality
- **Book Management**: Add, edit, delete, and search books
- **Member Management**: Student registration and account management
- **Transaction Processing**: Borrow/return books with automatic tracking
- **Fine Management**: Automatic overdue calculation and collection
- **Inventory Control**: Track book availability and status

## 🚀 Quick Start

### Prerequisites
- PHP 7.0 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- WAMP/XAMPP/MAMP (for local development)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/digital-library-management-system.git
   cd digital-library-management-system
   ```

2. **Setup Database**
   - Create database: `library_management`
   - Import: `database/library_management.sql`
   - Update credentials in `config/database.php`

3. **Configure Web Server**
   - Copy project to web server directory
   - Set appropriate file permissions
   - Start Apache and MySQL services

4. **Access the System**
   - Main site: `http://localhost/digital-library-management-system/`
   - Admin panel: `http://localhost/digital-library-management-system/librarian/`

### Default Login Credentials

#### Admin Access
- **URL**: `/librarian/login.php`
- **Username**: `admin`
- **Password**: `admin123`

#### Student Access
- **URL**: `/login.php`
- **Username**: `STU001`, `STU002`, `STU003`
- **Password**: `admin123`

## 📱 PWA Features

### Install as Mobile App
1. Open the system in Chrome/Edge
2. Look for "Install" button in address bar
3. Click to install as desktop/mobile app

### Offline Functionality
- System works offline after first visit
- Cached data available without internet
- Automatic sync when connection restored

## 🛠️ Technology Stack

### Frontend
- **Bootstrap 5.3** - Modern responsive framework
- **Font Awesome 6** - Professional icon library
- **Chart.js 4.x** - Interactive charts and graphs
- **Modern JavaScript** - ES6+, Fetch API, Service Workers

### Backend
- **PHP 7+** - Server-side scripting
- **PDO** - Secure database connections
- **MySQL** - Relational database management
- **Security** - CSRF protection, password hashing

### PWA Technologies
- **Service Workers** - Offline functionality
- **Web App Manifest** - App installation
- **Cache API** - Data caching
- **Push Notifications** - Real-time alerts

## 📁 Project Structure

```
digital-library-management-system/
├── api/                          # API endpoints
│   ├── dashboard_stats.php
│   ├── notifications.php
│   ├── recent_activity.php
│   └── search.php
├── assets/                       # Modern assets
│   ├── css/custom.css
│   └── js/app.js
├── components/                   # Reusable components
│   ├── accessibility.php
│   ├── loading-skeleton.php
│   ├── navbar.php
│   └── theme-toggle.php
├── config/                       # Configuration
│   ├── database.php
│   └── security.php
├── database/                     # Database files
│   └── library_management.sql
├── includes/                     # Include files
│   └── email.php
├── librarian/                    # Admin panel
├── manifest.json                 # PWA manifest
├── sw.js                         # Service worker
├── offline.html                  # Offline page
└── [Core PHP files]              # Main application
```

## 🔧 Configuration

### Database Configuration
Update `config/database.php`:
```php
private $host = 'localhost';
private $db_name = 'library_management';
private $username = 'your_username';
private $password = 'your_password';
```

### Email Configuration
Update `includes/email.php`:
```php
$this->smtp_host = 'smtp.gmail.com';
$this->smtp_username = 'your-email@gmail.com';
$this->smtp_password = 'your-app-password';
```

## 📊 Features Overview

### For Librarians
- ✅ Complete book inventory management
- ✅ Member registration and management
- ✅ Book issuing and return processing
- ✅ Fine calculation and collection
- ✅ Comprehensive reporting system
- ✅ User account management
- ✅ Analytics dashboard with charts
- ✅ Email notification system

### For Students
- ✅ Book search and availability checking
- ✅ Personal borrowing history
- ✅ Due date notifications
- ✅ Account management
- ✅ Mobile-responsive interface

### Modern Features
- ✅ Progressive Web App (PWA)
- ✅ Real-time search functionality
- ✅ QR code generation and scanning
- ✅ Dark mode toggle
- ✅ Offline support
- ✅ Push notifications
- ✅ Advanced analytics

## 🔒 Security Features

- **Password Security**: bcrypt hashing with salt
- **SQL Injection Prevention**: PDO prepared statements
- **CSRF Protection**: Token-based form protection
- **Input Validation**: Comprehensive sanitization
- **Session Security**: Secure session management
- **Access Control**: Role-based permissions

## 📱 Mobile Support

- **Responsive Design**: Mobile-first approach
- **PWA Installation**: Install as mobile app
- **Touch-Friendly**: Optimized for touch devices
- **Offline Access**: Works without internet
- **Push Notifications**: Real-time mobile alerts

## 🤝 Contributing

We welcome contributions! Please feel free to:

1. **Fork the repository**
2. **Create a feature branch**: `git checkout -b feature/amazing-feature`
3. **Commit your changes**: `git commit -m 'Add amazing feature'`
4. **Push to the branch**: `git push origin feature/amazing-feature`
5. **Open a Pull Request**

### Development Guidelines
- Follow PSR-12 coding standards
- Write meaningful commit messages
- Add tests for new features
- Update documentation as needed

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

### Getting Help
- **Documentation**: Check the `PROJECT_REPORT.md` for detailed docs
- **Setup Guide**: See `SETUP_GUIDE.md` for installation help
- **Issues**: Create an issue for bugs or feature requests
- **Discussions**: Use GitHub Discussions for questions

### Common Issues
- **Database Connection**: Check MySQL service and credentials
- **Permission Errors**: Set proper file permissions
- **PWA Not Working**: Ensure HTTPS in production
- **Email Not Sending**: Configure SMTP settings

## 🎯 Roadmap

### Planned Features
- [ ] Mobile app (React Native)
- [ ] Advanced analytics with ML
- [ ] Multi-language support
- [ ] API for third-party integrations
- [ ] Advanced reporting system
- [ ] Cloud deployment guides

## 🙏 Acknowledgments

- **Bootstrap** - For the amazing CSS framework
- **Font Awesome** - For the beautiful icons
- **Chart.js** - For interactive charts
- **PHP Community** - For excellent documentation

## 📞 Contact

- **Project**: Digital Library Management System
- **Author**: Your Name
- **Email**: your.email@example.com
- **GitHub**: [@yourusername](https://github.com/yourusername)

---

**⭐ If you found this project helpful, please give it a star!**

[![GitHub stars](https://img.shields.io/github/stars/yourusername/digital-library-management-system.svg?style=social&label=Star)](https://github.com/yourusername/digital-library-management-system)
[![GitHub forks](https://img.shields.io/github/forks/yourusername/digital-library-management-system.svg?style=social&label=Fork)](https://github.com/yourusername/digital-library-management-system/fork)
[![GitHub watchers](https://img.shields.io/github/watchers/yourusername/digital-library-management-system.svg?style=social&label=Watch)](https://github.com/yourusername/digital-library-management-system)