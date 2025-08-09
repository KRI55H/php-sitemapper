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

    public function testAddUrlWithAllAttributes(): void
    {
        $this->sitemapper
            ->addUrl('https://example.com/about', 0.8, '2025-01-22', 'daily')
            ->save();

        $xml = $this->sitemapper->generateXml();

        $this->assertStringContainsString('<loc>https://example.com/about</loc>', $xml);
        $this->assertStringContainsString('<lastmod>2025-01-22</lastmod>', $xml);
        $this->assertStringContainsString('<changefreq>daily</changefreq>', $xml);
        $this->assertStringContainsString('<priority>0.8</priority>', $xml);
    }

    public function testAddMultipleUrls(): void
    {
        $this->sitemapper
            ->addUrl('https://example.com/')->save()
            ->addUrl('https://example.com/about')->save()
            ->addUrl('https://example.com/contact')->save();

        $xml = $this->sitemapper->generateXml();

        $this->assertStringContainsString('<loc>https://example.com/</loc>', $xml);
        $this->assertStringContainsString('<loc>https://example.com/about</loc>', $xml);
        $this->assertStringContainsString('<loc>https://example.com/contact</loc>', $xml);
    }

    public function testSaveToFile(): void
    {
        $this->sitemapper
            ->addUrl('https://example.com/about')
            ->save();

        $filePath = __DIR__ . '/sitemap.xml';
        $this->sitemapper->saveToFile($filePath);

        $this->assertFileExists($filePath);

        $xmlContent = file_get_contents($filePath);
        $this->assertStringContainsString('<loc>https://example.com/about</loc>', $xmlContent);

        unlink($filePath);
    }

    public function testThrowsExceptionWhenSavingWithoutLoc(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("URL location is required before saving.");

        $this->sitemapper->save();
    }
}
