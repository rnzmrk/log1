# Gmail Configuration for OTP Emails

## Step 1: Enable 2-Factor Authentication
1. Go to your Google Account: https://myaccount.google.com/
2. Click on "Security" in the left menu
3. Scroll down to "Signing in to Google"
4. Click on "2-Step Verification" and enable it

## Step 2: Create App Password
1. After enabling 2-Step Verification, go back to Security settings
2. Click on "App passwords" (it will appear under "Signing in to Google")
3. Select "Mail" for the app
4. Select "Other (Custom name)" for the device
5. Name it something like "Laravel OTP System"
6. Click "Generate"
7. Copy the 16-character password (this is your app password)

## Step 3: Update .env File
Add these lines to your .env file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-character-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Step 4: Test the System
1. Run: php artisan config:clear
2. Try to login with your email and password
3. Check your Gmail inbox for the OTP code
4. Enter the code in the OTP verification form

## Important Notes:
- Use the App Password, NOT your regular Gmail password
- The App Password is 16 characters (including spaces)
- Keep your App Password secure
- Each App Password can only be used once per application

## Alternative Email Providers:
- Outlook: smtp.office365.com :587
- Yahoo: smtp.mail.yahoo.com :587
- SendGrid: smtp.sendgrid.net :587
