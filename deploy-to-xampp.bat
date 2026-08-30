@echo off
REM Copy this project into XAMPP htdocs as prisha-enterprises
set SRC=%~dp0
set DEST=C:\xampp\htdocs\prisha-enterprises
if not exist "C:\xampp\htdocs" (
  echo XAMPP htdocs not found at C:\xampp\htdocs
  echo Install XAMPP first, then run this script again.
  pause
  exit /b 1
)
echo Copying to %DEST% ...
xcopy "%SRC%*" "%DEST%\" /E /I /Y /EXCLUDE:%SRC%deploy-exclude.txt
echo Done. Open http://localhost/prisha-enterprises/
pause
