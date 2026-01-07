@echo off
SET VBOX_PATH="C:\Program Files\Oracle\VirtualBox"
SET VM_NAME="attm_default_1761612602820_79438"

echo Menjalankan %VM_NAME% dalam mode Headless...

:: Masuk ke direktori VirtualBox
cd /d %VBOX_PATH%

:: Menjalankan VM
VBoxManage startvm %VM_NAME% --type headless

echo.
echo VM telah dijalankan. Jendela ini bisa ditutup.
pause
