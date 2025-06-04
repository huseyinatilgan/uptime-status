# Uptime Status Dashboard

A modern, responsive status page for monitoring your services using UptimeRobot API. This dashboard provides real-time status information about your monitored services with a beautiful UI.

![Dashboard Preview](https://i.imgur.com/example.png)

## Features

- 🎨 Modern and responsive design using Tailwind CSS
- 📊 Real-time service status monitoring
- ⚡ Fast response time tracking
- 📱 Mobile-friendly interface
- 🔄 Automatic status updates
- 🎯 Detailed service information
- 🌐 Multi-language support (English/Turkish)

## Requirements

- PHP 8.1 or higher
- Composer
- Laravel 10.x
- UptimeRobot API Key

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/uptime-status.git
cd uptime-status
```

2. Install dependencies:
```bash
composer install
```

3. Create environment file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Configure your UptimeRobot API key in `.env` file:
```bash
UPTIMEROBOT_API_KEY=your_api_key_here
```

6. Start the development server:
```bash
php artisan serve
```

Visit `http://localhost:8000` to see your status page.

## Getting UptimeRobot API Key

1. Sign up for a free account at [UptimeRobot](https://uptimerobot.com)
2. Go to My Settings > API Settings
3. Create a new API key or use an existing one
4. Copy the API key to your `.env` file

## Configuration

The dashboard can be configured through the `.env` file:

```env
UPTIMEROBOT_API_KEY=your_api_key_here
APP_NAME="Your Status Page"
APP_URL=http://localhost:8000
```

## Security

- Never commit your `.env` file to version control
- Keep your UptimeRobot API key secure
- Use environment variables for sensitive data

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- [UptimeRobot](https://uptimerobot.com) for providing the monitoring API
- [Laravel](https://laravel.com) for the amazing framework
- [Tailwind CSS](https://tailwindcss.com) for the beautiful UI components

## Support

If you have any questions or need help, please open an issue in the GitHub repository.

## Author

Your Name - [@yourusername](https://github.com/yourusername)

---

Made with ❤️ by [Your Name]
