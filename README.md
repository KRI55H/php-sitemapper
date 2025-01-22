# php-sitemapper

**php-sitemapper** is a lightweight and powerful PHP library designed to generate dynamic XML sitemaps effortlessly. This library helps developers enhance their website's SEO by creating search-engine-friendly sitemaps, making it suitable for both small and large-scale projects. Fully compatible with Laravel and other PHP frameworks.

---

## Features

- **Dynamic Sitemap Generation**: Easily add URLs with optional metadata like `lastmod`, `changefreq`, and `priority`.
- **Base URL Support**: Simplify URL management by setting a base URL for all sitemap entries.
- **SEO-Friendly**: Generate XML sitemaps that comply with search engine standards.
- **File Export**: Save the generated sitemap to a file for easy deployment.
- **Lightweight and Flexible**: Ideal for integration with any PHP project, including Laravel and other frameworks.

---

## Installation

Install the library via Composer:

```bash
composer require kri55h/php-sitemapper
```

---

## Usage

### Basic Example

```php
require 'vendor/autoload.php';

use Kri55h\SiteMapper;

$sitemapper = new SiteMapper();

// Set the base URL
$sitemapper->addBaseUrl('https://example.com');

// Add URLs to the sitemap
$sitemapper->addUrl('/about', '2025-01-22', 'daily', 0.8);
$sitemapper->addUrl('/contact', '2025-01-21', 'weekly', 0.5);

// Generate XML
$xml = $sitemapper->generateXml();

// Output the XML
header('Content-Type: application/xml');
echo $xml;

// Save to file
$sitemapper->saveToFile('sitemap.xml');
```

### Laravel Example

For Laravel projects, you can use `php-sitemapper` to generate sitemaps dynamically. Here's an example:

1. Install the package via Composer:

```bash
composer require kri55h/php-sitemapper
```

2. Use the library in a controller:

```php
namespace App\Http\Controllers;

use Kri55h\SiteMapper;

class SitemapController extends Controller
{
    public function generateSitemap()
    {
        $sitemapper = new SiteMapper();

        // Add URLs dynamically (e.g., from routes or database)
        $sitemapper->addUrl(route('about'), now()->toDateString(), 'daily', 0.8);
        $sitemapper->addUrl(route('contact'), now()->subDay()->toDateString(), 'weekly', 0.5);

        // Generate XML
        $xml = $sitemapper->generateXml();

        // Return XML response
        return response($xml, 200)->header('Content-Type', 'text/xml');
    }
}
```

3. Add a route for the sitemap:

```php
Route::get('/sitemap.xml', [SitemapController::class, 'generateSitemap']);
```

---

## Methods

### `addBaseUrl(string $baseUrl)`
Sets the base URL for all sitemap entries.

**Parameters:**
- `$baseUrl` (string): The base URL of the site (e.g., `https://example.com`).

### `addUrl(string $loc, ?string $lastmod = null, ?string $changefreq = null, ?float $priority = null)`
Adds a URL to the sitemap with optional metadata.

**Parameters:**
- `$loc` (string): The URL location (relative or absolute).
- `$lastmod` (string|null): The last modification date in `YYYY-MM-DD` format (optional).
- `$changefreq` (string|null): The change frequency (e.g., `daily`, `weekly`) (optional).
- `$priority` (float|null): The priority of the URL (0.0 to 1.0) (optional).

### `generateXml(): string`
Generates the XML sitemap as a string.

### `saveToFile(string $filePath): void`
Saves the generated XML sitemap to a file.

**Parameters:**
- `$filePath` (string): The file path where the sitemap should be saved.

---

## Requirements

- PHP 7.4 or higher
- `ext-json` enabled

---

## License

This project is licensed under the [MIT License](LICENSE).

---

## Contributing

Contributions are welcome! Please feel free to submit issues or pull requests to improve this library.

---

## Author

Developed by **Krish Siddhapura**

- Email: siddhapurakrish007@gmail.com
- GitHub: [KRI55H](https://github.com/KRI55H)

---

## Keywords

- PHP sitemap generator
- Dynamic XML sitemap
- SEO-friendly sitemap library
- Generate sitemaps in PHP
- Lightweight PHP sitemap tool
- Laravel sitemap generator
- Dynamic sitemap generator
- Sitemapper PHP
- Laravel XML sitemap

