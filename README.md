# DevSpark — Software Development Landing Page

A modern, responsive landing page built with Bootstrap 5.3.3. Includes a working contact form handler in PHP with basic validation, session rate limiting, and local logging fallback when email is not configured.

## Live Preview (Local)
- URL: `http://localhost/landing/`
- Root path: `c:/xampp/htdocs/landing`

## Project Structure
```
landing/
├─ index.html               # Main landing page
├─ contact.php              # Handles POST from contact form
├─ assets/
│  ├─ css/
│  │  └─ style.css         # Custom styles
│  ├─ js/
│  │  └─ main.js           # Interactions (scroll, alerts)
│  └─ logs/                # Auto-created for contact form logs
└─ README.md
```

## Features
- Sticky navbar, smooth scrolling, and ScrollSpy.
- Sections: Hero, Services, Process, Work, Testimonials, Pricing, Contact.
- Contact form with validation (client + server), rate limiting, and feedback alerts.
- Bootstrap Icons and lightweight custom CSS.

## Requirements
- XAMPP (Apache + PHP) on Windows.
- Internet access for CDN assets (Bootstrap CSS/JS, icons, demo images).

## Setup
1. Ensure the project files are placed at `c:/xampp/htdocs/landing`.
2. Start Apache in the XAMPP Control Panel.
3. Open: http://localhost/landing/

## Contact Form
- Form posts to `contact.php` with fields: `name`, `email`, `company`, `message`.
- Server logic:
  - Validates required fields and email.
  - Simple rate limit: 1 submission per 10s per session.
  - Tries `mail()`; on failure logs the message to `assets/logs/contacts-YYYY-MM-DD.log`.
  - Redirects back to `index.html` with `?status=ok|error&msg=...`.
- Client feedback: `assets/js/main.js` reads URL params and shows a Bootstrap alert in `#formAlert`.

### Configure recipient email
In `contact.php`, update the recipient address:
```php
$to = 'hello@devspark.agency'; // change to your inbox
```

### Email on XAMPP
- PHP `mail()` is often not configured on local XAMPP. In this case, submissions are saved to `assets/logs/` and you will see a success alert indicating it was logged.
- For real email delivery, switch to SMTP using PHPMailer:
  - Create an SMTP account (e.g., Gmail with App Password, SendGrid, Mailgun).
  - I can add a `mailer.php` using PHPMailer on request.

## Customization
- Branding/colors: Edit `index.html` (brand text) and `assets/css/style.css` (`--ds-primary`).
- Images: Replace Unsplash/dummy image URLs in `index.html` with your assets.
- Sections: Update copy under `#services`, `#process`, `#work`, `#pricing`, `#contact`.
- Icons: Use any from Bootstrap Icons (`https://icons.getbootstrap.com/`).

## Development Tips
- Keep HTML semantic and accessible (labels, alt text, button text).
- Minify images for performance.
- If hosting publicly, consider:
  - SEO meta and Open Graph tags.
  - Analytics.
  - Cookie/Privacy policy links in footer.

## Troubleshooting
- Page not loading: Ensure Apache is running and files are under `c:/xampp/htdocs/landing`.
- Form shows red alert:
  - Check field values and rate limit.
  - Open `assets/logs/` to confirm logged submissions.
  - Configure SMTP if you need real delivery.

## License
This template is provided as-is for personal or commercial use. Attribution appreciated but not required.
