<?php

namespace Kri55h;

use SimpleXMLElement;

/**
 * Class SiteMapper
 *
 * A simple sitemap generator that supports fluent method chaining.
 * Allows adding URLs with priority, last modified date, and change frequency,
 * then generates valid XML output according to the sitemap protocol.
 *
 * Example usage:
 * 
 * $map = new SiteMapper();
 * $map->addUrl('/about', 0.8, '2025-08-09', 'daily')->save();
 * $map->addUrl('/contact', 0.8, '2025-08-09', 'daily')->save();
 * $map->outputXml();
 */
class SiteMapper
{
    /** @var array Stores all saved URLs for the sitemap. */
    private array $urls = [];

    /** @var array Temporarily stores the current URL data before saving. */
    private array $object = [];

    /**
     * Add a URL entry to the sitemap (staged until save() is called).
     *
     * @param string      $location         The URL location (relative or absolute).
     * @param float|null  $priority         The priority of the URL (0.0 to 1.0).
     * @param string|null $last_modified    The last modification date in YYYY-MM-DD format.
     * @param string|null $change_frequency How frequently the page changes (always, hourly, daily, weekly, monthly, yearly, never).
     *
     * @return $this
     */
    public function addUrl(
        string $location,
        ?float $priority = null,
        ?string $last_modified = null,
        ?string $change_frequency = null
    ): self {
        $this->object['loc'] = $location;

        if ($priority !== null) {
            $this->setPriority($priority);
        }
        if ($last_modified !== null) {
            $this->setLastModified($last_modified);
        }
        if ($change_frequency !== null) {
            $this->setChangeFrequency($change_frequency);
        }

        return $this;
    }

    /**
     * Set the priority for the current URL (before saving).
     *
     * @param float $priority A number between 0.0 and 1.0.
     * @return $this
     */
    public function setPriority(float $priority): self
    {
        $this->object['priority'] = $priority;
        return $this;
    }

    /**
     * Set the last modified date for the current URL (before saving).
     *
     * @param string $last_modified Date in YYYY-MM-DD format.
     * @return $this
     */
    public function setLastModified(string $last_modified): self
    {
        $this->object['lastmod'] = $last_modified;
        return $this;
    }

    /**
     * Set the change frequency for the current URL (before saving).
     *
     * @param string $change_frequency Allowed values: always, hourly, daily, weekly, monthly, yearly, never.
     * @return $this
     */
    public function setChangeFrequency(string $change_frequency): self
    {
        $this->object['changefreq'] = $change_frequency;
        return $this;
    }

    /**
     * Save the current staged URL to the sitemap list.
     * This must be called after addUrl() to commit the entry.
     *
     * @throws \RuntimeException If 'loc' (location) is missing.
     * @return $this
     */
    public function save(): self
    {
        if (!isset($this->object['loc'])) {
            throw new \RuntimeException("URL location is required before saving.");
        }
        $this->urls[] = $this->object;
        $this->object = [];
        return $this;
    }

    /**
     * Generate the XML string for the sitemap.
     *
     * @return string XML string in Sitemap protocol format.
     */
    public function generateXml(): string
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset></urlset>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xml->addAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');

        foreach ($this->urls as $url) {
            $urlElement = $xml->addChild('url');
            $urlElement->addChild('loc', htmlspecialchars($url['loc'], ENT_QUOTES, 'UTF-8'));

            if (isset($url['lastmod'])) {
                $urlElement->addChild('lastmod', $url['lastmod']);
            }
            if (isset($url['changefreq'])) {
                $urlElement->addChild('changefreq', $url['changefreq']);
            }
            if (isset($url['priority'])) {
                $urlElement->addChild('priority', number_format($url['priority'], 1, '.', ''));
            }
        }

        return $xml->asXML();
    }

    /**
     * Output the sitemap directly to the browser with proper headers.
     * This is useful for returning sitemaps directly from a controller.
     *
     * @return void
     */
    public function outputXml(): void
    {
        header('Content-Type: application/xml; charset=UTF-8');
        echo $this->generateXml();
    }

    /**
     * Save the sitemap XML to a file on disk.
     *
     * @param string $filePath Full path to save the XML file.
     * @return void
     */
    public function saveToFile(string $filePath): void
    {
        file_put_contents($filePath, $this->generateXml());
    }
}
