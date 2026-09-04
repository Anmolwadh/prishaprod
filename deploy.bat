@echo off
setlocal enabledelayedexpansion
title Prisha Enterprises - Deploy to Dev, Prod & Live Server
cd /d "%~dp0"

echo ========================================================
echo   Prisha Enterprises: Automated Deployment Pipeline
echo   Target 1: GitHub prishadev  (Development)
echo   Target 2: GitHub prishaprod (Production)
echo   Target 3: Live Server       (prisha-enterprises.in)
echo ========================================================
echo.

rem Check git status
git status --porcelain > "%TEMP%\git_status.txt"
set HAS_CHANGES=0
for %%A in ("%TEMP%\git_status.txt") do if %%~zA gtr 0 set HAS_CHANGES=1

if %HAS_CHANGES%==1 (
    echo [INFO] Detected uncommitted changes:
    echo ----------------------------------------------------
    git status -s
    echo ----------------------------------------------------
    echo.
    set /p COMMIT_MSG="Enter commit description (or press ENTER for default): "
    if "!COMMIT_MSG!"=="" (
        for /f "tokens=2 delims==" %%I in ('wmic os get localdatetime /value') do set DT=%%I
        set COMMIT_MSG=Update website - !DT:~0,4!-!DT:~4,2!-!DT:~6,2! !DT:~8,2!:!DT:~10,2!
    )
    echo.
    echo [1/3] Staging and committing changes...
    git add -A
    git commit -m "!COMMIT_MSG!"
) else (
    echo [INFO] Working directory clean. Pushing latest commits...
)

echo.
echo [2/3] Pushing to GitHub (prishadev and prishaprod)...
git push origin main

if %ERRORLEVEL% neq 0 (
    echo.
    echo [ERROR] Push failed! Please check your internet connection or git permissions.
    pause
    exit /b 1
)

echo.
echo [3/3] Deployment triggered successfully!
echo ========================================================
echo   - prishadev:  https://github.com/Anmolwadh/prishadev
echo   - prishaprod: https://github.com/Anmolwadh/prishaprod
echo   - Live Site:  https://prisha-enterprises.in
echo ========================================================
echo.
echo GitHub Actions on prishaprod is now deploying changed files
echo to your live Hostomy server via secure FTP.
echo.
echo Opening deployment tracker in your browser...
start https://github.com/Anmolwadh/prishaprod/actions
echo.
pause
