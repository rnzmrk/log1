# Gmail SMTP Configuration for OTP

## 🔧 Configure Gmail to Send OTP Emails

### Step 1: Enable 2-Factor Authentication
1. Go to your Google Account: https://myaccount.google.com/
2. Click on "Security"
3. Enable "2-Step Verification"

### Step 2: Generate App Password
1. Go to Google Account Security
2. Click on "App passwords"
3. Select "Mail" for app
4. Select "Other (Custom name)" and enter "Laravel OTP"
5. Click "Generate"
6. Copy the 16-character password (without spaces)

### Step 3: Update .env File
Add these lines to your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-16-character-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Step 4: Clear Cache
Run these commands:

```bash
php artisan config:clear
php artisan cache:clear
```

### Step 5: Test the System
1. Go to: http://127.0.0.1:8000/login
2. Enter: test@example.com / password123
3. Check your Gmail for OTP code
4. Enter OTP to login

## 📧 Alternative Email Services

### SendGrid
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=YOUR_SENDGRID_API_KEY
MAIL_ENCRYPTION=tls
```

### Mailtrap (Development)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```

## ✅ Security Notes

- Never commit your `.env` file to version control
- Use app-specific passwords, not your main password
- Consider using environment-specific configurations
- Enable "Less secure app access" only if necessary (not recommended)

## 🚀 Troubleshooting

### Common Issues:
1. **"Authentication failed"** - Check app password
2. **"Connection refused"** - Check SMTP settings
3. **"SSL/TLS error"** - Try different encryption (tls/ssl)
4. **No email received** - Check spam folder

### Debug Mode:
Add to `.env` to see email errors:
```env
MAIL_ENCRYPTION=null
```

Remember to set it back to `tls` for production!
