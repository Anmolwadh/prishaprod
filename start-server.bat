@echo off
title Prisha Enterprises - Local Server
cd /d "%~dp0"

set PHP=C:\Users\admin\AppData\Local\Programs\php\php.exe
set MYSQLD=C:\Program Files\MariaDB 12.3\bin\mysqld.exe
set MYSQL=C:\Program Files\MariaDB 12.3\bin\mysql.exe
set USERDATA=C:\Users\admin\mariadb-data
set ORIGDATA=C:\Program Files\MariaDB 12.3\data

if not exist "%PHP%" (
  echo PHP not found at %PHP%
  pause
  exit /b 1
)

if not exist "%USERDATA%\ibdata1" (
  echo Preparing MariaDB data folder...
  mkdir "%USERDATA%" >nul 2>&1
  xcopy "%ORIGDATA%\*" "%USERDATA%\" /E /I /Y /Q
)

echo [mysqld]> "%USERDATA%\my.ini"
echo datadir=C:/Users/admin/mariadb-data>> "%USERDATA%\my.ini"
echo port=3306>> "%USERDATA%\my.ini"
echo innodb_buffer_pool_size=256M>> "%USERDATA%\my.ini"
echo [client]>> "%USERDATA%\my.ini"
echo port=3306>> "%USERDATA%\my.ini"

echo Starting MariaDB...
start "" /MIN "%MYSQLD%" --defaults-file="%USERDATA%\my.ini"
timeout /t 5 /nobreak >nul

echo Starting website at http://127.0.0.1:8080/
echo Keep this window OPEN while using the site.
echo.
"%PHP%" -S 127.0.0.1:8080 -t "%~dp0"
pause
