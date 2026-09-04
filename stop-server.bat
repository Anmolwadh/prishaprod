@echo off
title Stop Prisha Enterprises Local Server
echo Stopping PHP built-in server...
powershell -NoProfile -Command "Get-Process php -ErrorAction SilentlyContinue | Stop-Process -Force" >nul 2>&1

echo Stopping MariaDB...
powershell -NoProfile -Command "Get-Process mysqld -ErrorAction SilentlyContinue | Stop-Process -Force" >nul 2>&1

echo.
echo [OK] Local PHP server and MariaDB have been stopped.
powershell -NoProfile -Command "Start-Sleep -Seconds 2"
