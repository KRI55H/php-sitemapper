# Changelog

## [2.0.0] — 2025-08-09

### 🚨 Breaking Changes
- **Completely refactored API** — now supports a fluent, chainable workflow.
- `addBaseUrl()` **removed** — base URL handling is now manual or should be prepended before calling `addUrl()`.
- `addUrl()` signature changed — no longer directly stores URLs; instead, stages data until `save()` is called.
- All URL attribute setters (`priority`, `lastmod`, `changefreq`) are now separate chainable methods (`setPriority()`, `setLastModified()`, `setChangeFrequency()`).
- `generateXml()` now works only with URLs that have been committed using `save()`.
- XML output formatting for priority now uses `number_format(..., 1, '.', '')` for consistent decimal formatting.

### 🗑️ Deprecated / Removed
- `addBaseUrl()` → **Removed**. You must now prepend the base URL yourself.
- Old-style `addUrl()` that directly pushed into `$urls` → **Removed**.
- Direct `addUrl(...)->generateXml()` usage → **Deprecated**. Must now use `save()` before generating XML.

### ✨ New Functions
- `setPriority(float $priority): self`
- `setLastModified(string $last_modified): self`
- `setChangeFrequency(string $change_frequency): self`
- `save(): self` — commits staged URL to list.
- `outputXml(): void` — sends XML directly to the browser with correct headers.

### 📚 Migration Guide

#### 1. Adding URLs

**Before (v1.x):**
```php
$map = new SiteMapper();
$map->addBaseUrl('https://example.com');
$map->addUrl('/about', 0.8, '2025-08-09', 'daily');
$map->addUrl('/contact', 0.8, '2025-08-09', 'daily');
echo $map->generateXml();
```

**After (v2.0.0):**
```php
$map = new SiteMapper();

$map->addUrl('/about', 0.8, '2025-08-09', 'daily')->save();
$map->addUrl('/contact', 0.8, '2025-08-09', 'daily')->save();

echo $map->generateXml();
```

#### 2. Adding URLs with separate setter

**Before (v1.x):**
```php
$map->addUrl('/blog', 0.5, '2025-08-09', 'weekly');
```

**After (v2.0.0):**
```php
$map->addUrl('/blog')
    ->setPriority(0.5)
    ->setLastModified('2025-08-09')
    ->setChangeFrequency('weekly')
    ->save();
```

#### 3. Directly outputting XML

**Before (v1.x):**
```php
header('Content-Type: application/xml');
echo $map->generateXml();
```

**After (v2.0.0):**
```php
$map->outputXml();
```