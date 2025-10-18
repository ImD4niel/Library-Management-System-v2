@echo off
echo ========================================
echo   Digital Library Management System
echo ========================================
echo.
echo Starting the system...
echo.

REM Check if XAMPP is installed
if exist "C:\xampp\xampp-control.exe" (
    echo Found XAMPP installation
    echo Starting XAMPP services...
    start "" "C:\xampp\xampp-control.exe"
    echo.
    echo Please start Apache and MySQL in XAMPP Control Panel
    echo Then open: http://localhost/Library-Management-System/
    echo.
) else (
    echo XAMPP not found in default location
    echo Please install XAMPP first from: https://www.apachefriends.org/download.html
    echo.
)

REM Check if WAMP is installed
if exist "C:\wamp\wampmanager.exe" (
    echo Found WAMP installation
    echo Starting WAMP services...
    start "" "C:\wamp\wampmanager.exe"
    echo.
    echo Please start all services in WAMP
    echo Then open: http://localhost/Library-Management-System/
    echo.
)

echo ========================================
echo   Setup Instructions:
echo ========================================
echo 1. Start Apache and MySQL services
echo 2. Open browser and go to:
echo    http://localhost/Library-Management-System/
echo 3. For admin panel:
echo    http://localhost/Library-Management-System/librarian/
echo.
echo Default Admin Login:
echo Username: admin
echo Password: admin123
echo.
echo ========================================
pause
