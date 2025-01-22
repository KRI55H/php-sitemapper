<?php

namespace Kri55h\Sitemapper\Tests;

use Kri55h\SiteMapper;
use PHPUnit\Framework\TestCase;

class SiteMapperTest extends TestCase
{
    private SiteMapper $sitemapper;

    protected function setUp(): void
    {
        $this->sitemapper = new SiteMapper();
    }

    public function testAddUrl(): void
    {
        // Set the base URL
        $this->sitemapper->addBaseUrl('https://example.com');

        // Add a URL to the sitemap
        $this->sitemapper->addUrl('/about', 0.8, '2025-01-22', 'daily');

        // Generate the XML sitemap
        $xml = $this->sitemapper->generateXml();

        // Check if the generated XML contains the expected URL
        $this->assertStringContainsString('<loc>https://example.com/about</loc>', $xml);
        $this->assertStringContainsString('<lastmod>2025-01-22</lastmod>', $xml);
        $this->assertStringContainsString('<changefreq>daily</changefreq>', $xml);
        $this->assertStringContainsString('<priority>0.8</priority>', $xml);
    }

    public function testGenerateXml(): void
    {
        // Set the base URL
        $this->sitemapper->addBaseUrl('https://example.com');

        // Add multiple URLs to the sitemap
        $this->sitemapper->addUrl('/');
        $this->sitemapper->addUrl('/about');
        $this->sitemapper->addUrl('/contact');

        // Generate the XML sitemap
        $xml = $this->sitemapper->generateXml();

        // Check if the generated XML contains all the expected URLs
        $this->assertStringContainsString('<loc>https://example.com/</loc>', $xml);
        $this->assertStringContainsString('<loc>https://example.com/about</loc>', $xml);
        $this->assertStringContainsString('<loc>https://example.com/contact</loc>', $xml);
    }

    public function testSaveToFile(): void
    {
        // Set the base URL
        $this->sitemapper->addBaseUrl('https://example.com');

        // Add a URL to the sitemap
        $this->sitemapper->addUrl('/about');

        // Save the sitemap to a file
        $filePath = 'sitemap.xml';
        $this->sitemapper->saveToFile($filePath);

        // Check if the file was created
        $this->assertFileExists($filePath);

        // Read the content of the file
        $xmlContent = file_get_contents($filePath);

        // Check if the content contains the expected URL
        $this->assertStringContainsString('<loc>https://example.com/about</loc>', $xmlContent);

        // Clean up the generated file
        unlink($filePath);
    }
}
