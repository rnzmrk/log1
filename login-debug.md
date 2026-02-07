# Login Debug Instructions

## Quick Test Steps:

### 1. Make sure your .env has:
```env
APP_URL=http://127.0.0.1:8000
SESSION_DRIVER=file
SESSION_DOMAIN=
SESSION_SECURE_COOKIE=false
```

### 2. Clear caches (already done):
```bash
php artisan config:clear
php artisan cache:clear
```

### 3. Test with these credentials:
- **Email**: test@example.com  
- **Password**: password123

### 4. Run this to create test user:
```bash
php artisan tinker
```
Then in tinker:
```php
$user = new App\Models\User();
$user->name = 'Test User';
$user->email = 'test@example.com';
$user->password = Hash::make('password123');
$user->save();
exit
```

### 5. Test Login Flow:
1. Go to: http://127.0.0.1:8000/login
2. Enter: test@example.com / password123
3. Should see: "OTP sent to your email"
4. Check browser console for any errors
5. If email not configured, you'll see error message

### 6. If still getting 419:
- Open browser **incognito window**
- Make sure URL is exactly: http://127.0.0.1:8000/login
- Check Network tab in browser dev tools
- Look for the POST /login request details

### 7. Email Configuration (optional):
If you want actual emails, configure in .env:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
```
