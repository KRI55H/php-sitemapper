# SiteMapper

**SiteMapper** is a lightweight, chainable PHP library to generate SEO-friendly XML sitemaps dynamically.  
Designed for modern PHP projects and frameworks (Laravel, Codeigniter, Symfony, plain PHP). 

---

## Features

- Fluent API: `addUrl(...)->save()` for concise, readable code.  
- Add per-URL metadata: `priority`, `lastmod` and `changefreq`.  
- `generateXml()` returns an XML string ready to return from controllers.  
- `outputXml()` sends correct headers and echoes XML directly.  
- `saveToFile()` writes sitemap XML to disk.  
- Minimal dependencies — requires only PHP and SimpleXML.

---

## Requirements

- PHP **7.4** or newer  
- `ext-simplexml` enabled

---

## Installation

Install via Composer:

```bash
composer require kri55h/php-sitemapper
```
Then autoload:

```php
require 'vendor/autoload.php';

use Kri55h\SiteMapper;
```

## Quick Example (recommended)
```php
<?php
require 'vendor/autoload.php';

use Kri55h\SiteMapper;

$map = new SiteMapper();

// Add and save entries (pass full URLs)
$map->addUrl('https://example.com/about', 0.8, '2025-08-09', 'daily')->save()
    ->addUrl('https://example.com/contact', 0.5, '2025-08-09', 'weekly')->save();

// Return XML from a controller or script
header('Content-Type: application/xml; charset=UTF-8');
echo $map->generateXml();
```
**Important:** ``addUrl()`` stages a URL in memory. Call ``save()`` to commit the staged entry into the sitemap list — otherwise it will not appear in ``generateXml()``.

## Full Example (all functions / backwards-compatible style)
```php
<?php
require 'vendor/autoload.php';

use Kri55h\SiteMapper;

$map = new SiteMapper();

// Staged style: call setters, then save()
$map->addUrl('https://example.com/')
    ->setPriority(1.0)
    ->setLastModified('2025-08-09')
    ->setChangeFrequency('daily')
    ->save();

// Another entry, using positional args
$map->addUrl('https://example.com/about', 0.8, '2025-08-09', 'weekly')->save();

// Save sitemap to disk
$map->saveToFile(__DIR__ . '/public/sitemap.xml');

// Or output directly (sets header + echoes XML)
$map->outputXml();
```
## Public API Reference
- ``addUrl(string $location, ?float $priority = null, ?string $last_modified = null, ?string $change_frequency = null): self``
Adds a staged URL entry. Pass a full URL (https://example.com/page). Returns $this for chaining.

- ``setPriority(float $priority): self``
Set priority (0.0 — 1.0) for the currently staged entry. Chainable.

- ``setLastModified(string $last_modified): self``
Set the last modified date (YYYY-MM-DD) for the currently staged entry. Chainable.

- ``setChangeFrequency(string $change_frequency): self``
Set change frequency (always|hourly|daily|weekly|monthly|yearly|never). Chainable.

- ``save(): self``
Commit the currently staged entry into the sitemap list. Throws RuntimeException if no loc was staged. Chainable.

- ``generateXml(): string``
Generate and return the sitemap XML string. Use this to return XML from controller routes.

- ``outputXml(): void``
Send Content-Type: application/xml; charset=UTF-8 and echo the generated XML. Convenience helper for quick endpoints.

- ``saveToFile(string $filePath): void``
Write the generated sitemap XML to the given file path.

## Migration notes (from previous versions that had ``addBaseUrl()``)
If you upgraded from a version that supported addBaseUrl() and relative paths:
- **Before (old):**
```php
$map->addBaseUrl('https://example.com');
$map->addUrl('/about');
echo $map->generateXml();
```
- **Now (new):**
```php
$map->addUrl('https://example.com/about')->save();
echo $map->generateXml();
```
**Why**: The library no longer maintains global base URL state. This makes the API explicit and safer for mixed-domain usages.

## Best practices
- Always call ``save()`` after ``addUrl()`` (or use positional arguments with ``addUrl(...)->save()``).

- Use ``generateXml()`` to return XML from controllers (so the framework can manage responses).

- Use ``outputXml()`` only for simple scripts or endpoints where you want the library to send headers.

- For bulk imports, add entries in a loop and call ``save()`` per entry, or implement your own batching helper.

## License
This project is licensed under the ``MIT`` License.


##  Author

Krish Siddhapura — siddhapurakrish007@gmail.com — https://github.com/KRI55H

## Keywords (for SEO)

PHP sitemap generator, XML sitemap, SEO sitemap PHP, SiteMapper, sitemap generator, Laravel sitemap generator, dynamic sitemap, Laravel dynamic sitemap generator.