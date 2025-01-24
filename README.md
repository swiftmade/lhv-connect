# LHV Connect for Laravel

<p align="center">
    <img src="https://img.shields.io/packagist/v/swiftmade/lhv-connect.svg" alt="Latest Stable Version">
    <img src="https://img.shields.io/packagist/l/swiftmade/lhv-connect.svg" alt="License">
    <img src="https://img.shields.io/packagist/php-v/swiftmade/lhv-connect.svg" alt="PHP Version">
</p>

> **Legal Disclaimer**: This is a third-party integration package. It is not officially endorsed, sponsored, affiliated with or otherwise authorized by AS LHV Pank. All product and company names are trademarks™ or registered® trademarks of their respective holders.

A Laravel integration package for LHV Connect API, providing secure banking operations for Estonian businesses. This package handles the complexities of LHV's API communication, certificate management, and response handling.

## Features

- Account balance inquiries
- Account statements
- Secure certificate-based authentication
- Automatic message handling and cleanup
- Comprehensive error handling
- Sandbox environment support

## Requirements

- PHP 8.1 or higher
- Laravel 8.x, 9.x, 10.x, 11.x
- Valid LHV Connect API credentials and certificates

## Installation

```bash
composer require swiftmade/lhv-connect
```

## Configuration

1. Publish the configuration file:

```bash
php artisan vendor:publish --provider="Swiftmade\LhvConnect\LhvConnectServiceProvider"
```

2. Configure your credentials in `config/lhv-connect.php`:
   - Set paths to your .p12 certificate files
   - Configure certificate passwords
   - Add your IBAN and account details
   - Set up both sandbox and production environments as needed

Example configuration:
```php
'sandbox' => [
    'url' => 'https://connect.prelive.lhv.eu',
    'cert' => [
        'path' => '/path/to/cert.p12',
        'password' => 'your-cert-password',
    ],
    'verify' => 'path_to_lhv_rootca.cer',
    'IBAN' => 'EE123456789',
    'name' => 'Company Name',
    'bic' => 'LHVBEE22',
],
```

## Usage

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

## Error Handling

The package implements comprehensive error handling for:
- Invalid configuration
- API errors (LhvApiError)
- Connection issues
- Request timeouts

## Technical Details

- Uses certificate-based authentication
- Implements request locking mechanism
- Default request timeout: 2 seconds
- Automatic retry mechanism with exponential backoff
- Automatic cleanup of processed messages

## Contributing

Contributions are welcome. Please ensure your changes adhere to the following:
- Follow PSR-12 coding standards
- Add/update tests as needed
- Document new features

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Resources

- [LHV Connect API Documentation](https://www.lhv.ee/en/connect)
- [Changelog](CHANGELOG.md)
- [Issue Tracker](https://github.com/swiftmade/lhv-connect/issues)

---

<p align="center">
Developed and maintained by Swiftmade OÜ
</p>
