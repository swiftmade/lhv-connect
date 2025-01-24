# LHV Connect for Laravel

<p align="center">
    <img src="https://img.shields.io/packagist/v/swiftmade/lhv-connect.svg" alt="Latest Stable Version">
    <img src="https://img.shields.io/packagist/l/swiftmade/lhv-connect.svg" alt="License">
    <img src="https://img.shields.io/packagist/php-v/swiftmade/lhv-connect.svg" alt="PHP Version">
</p>

A robust Laravel package for integrating with LHV Connect API. Simplify your banking operations with easy-to-use methods for account statements, balance inquiries, and more.

## 🚀 Features

- 🏦 Account balance inquiries
- 📊 Account statements
- 🔒 Secure communication with LHV Connect API
- ⚡️ Asynchronous request handling
- 🛡️ Built-in error handling and retries
- 🧪 Sandbox environment support

## 📋 Requirements

- PHP 8.1 or higher
- Laravel 8.x, 9.x, 10.x, 11.x
- Valid LHV Connect API credentials

## 📦 Installation

```bash
composer require swiftmade/lhv-connect
```

## ⚙️ Configuration

1. Publish the configuration file:

```bash
php artisan vendor:publish --provider="Swiftmade\LhvConnect\LhvConnectServiceProvider"
```

2. Configure your LHV Connect credentials in `config/lhv-connect.php`. You can set up multiple accounts (e.g., sandbox and production).

## 💻 Usage

### Initialize the Client

```php
use Swiftmade\LhvConnect\LhvConnect;

// Connect to sandbox or production
$lhv = LhvConnect::make('sandbox');
```

### Test Connection

```php
$lhv->sendHeartbeat();
```

### Get Account Balance

```php
// Get balance for default IBAN
$balance = $lhv->getAccountBalance();

// Or specify an IBAN
$balance = $lhv->getAccountBalance('EE123456789');
```

### Get Account Statement

```php
// Get statement for a date range
$statement = $lhv->getAccountStatement(
    fromDate: new DateTime('2024-01-01'),
    toDate: new DateTime('2024-03-01')
);

// Or include a specific IBAN
$statement = $lhv->getAccountStatement(
    fromDate: new DateTime('2024-01-01'),
    toDate: new DateTime('2024-03-01'),
    accountIban: 'EE123456789'
);
```

## 🛡️ Error Handling

The package includes comprehensive error handling for:
- ❌ Invalid configuration
- ❌ API errors (`LhvApiError`)
- ❌ Connection issues
- ❌ Request timeouts

## 🔍 Implementation Details

- Uses a locking mechanism for handling asynchronous responses
- Default request timeout: 2 seconds
- Implements exponential backoff for retries
- Automatic message cleanup

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgements

Based on the original package by [Mihkel Allorg](https://github.com/mihkelallorg/lhv-connect).

## 📚 Resources

- [LHV Connect Documentation](https://www.lhv.ee/en/connect)
- [Changelog](CHANGELOG.md)
- [Issue Tracker](https://github.com/swiftmade/lhv-connect/issues)

---

<p align="center">
Made with ❤️ for the Estonian developer community
</p>
