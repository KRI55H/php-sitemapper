<?php

namespace Kri55h;

use SimpleXMLElement;

class SiteMapper
{
    private array $urls = [];
    private string $baseUrl = "";

    /**
     * Set the base URL for the sitemap.
     *
     * @param string $baseUrl The base URL of the site (e.g., https://example.com).
     */
    public function addBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * Add a URL to the sitemap.
     *
     * @param string $loc        The URL location.
     * @param string|null $lastmod The last modification date (in YYYY-MM-DD format).
     * @param string|null $changefreq The frequency of changes (always, hourly, daily, weekly, monthly, yearly, never).
     * @param float|null $priority The priority of the URL (0.0 to 1.0).
     */
    public function addUrl(string $location,?float $priority = null, ?string $last_modified = null, ?string $change_frequency = null): void
    {
        if (!empty($this->baseUrl)) {
            $location = rtrim($this->baseUrl, '/') . '/' . ltrim($location, '/');
        }
        $this->urls[] = [
            'loc' => $location,
            'lastmod' => $last_modified,
            'changefreq' => $change_frequency,
            'priority' => $priority,
        ];
    }

    /**
     * Generate the XML sitemap.
     *
     * @return string The XML sitemap as a string.
     */
    public function generateXml(): string
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset></urlset>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xml->addAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');

        foreach ($this->urls as $url) {
            $urlElement = $xml->addChild('url');
            $urlElement->addChild('loc', htmlspecialchars($url['loc'], ENT_QUOTES, 'UTF-8'));
            if ($url['lastmod']) {
                $urlElement->addChild('lastmod', $url['lastmod']);
            }
            if ($url['changefreq']) {
                $urlElement->addChild('changefreq', $url['changefreq']);
            }
            if ($url['priority']) {
                $urlElement->addChild('priority', number_format($url['priority'], 1));
            }
        }

        return $xml->asXML();
    }

    /**
     * Save the XML sitemap to a file.
     *
     * @param string $filePath The file path where the sitemap should be saved.
     */
    public function saveToFile(string $filePath): void
    {
        file_put_contents($filePath, $this->generateXml());
    }
}