<?php

namespace Pieldefoca\Lux\Livewire\Seo;

use Livewire\Component;
use Livewire\Attributes\On;
use Spatie\Sitemap\SitemapGenerator;

class Index extends Component
{
    public $analyticsStatus = false;

    public $analyticsContent;

    public $robotsStatus = false;

    public $robotsContent;

    public $sitemapStatus = false;

    public $sitemapContent;

    public function mount()
    {
        $this->checkAnalytics();

        $this->checkRobots();

        $this->checkSitemap();
    }

    protected function checkAnalytics()
    {
        $analyticsPath = resource_path('views/components/seo/analytics.blade.php');

        if(!file_exists($analyticsPath)) { return $this->analyticsStatus = false; }

        $contents = file_get_contents($analyticsPath);

        $this->analyticsContent = $contents;

        if(empty($contents)) {
            $this->analyticsStatus = false;
        } else {
            $this->analyticsStatus = true;
        }
    }

    protected function checkRobots()
    {
        $robotsPath = public_path('robots.txt');

        if(!file_exists($robotsPath)) { return $this->robotsStatus = false; }

        $contents = file_get_contents($robotsPath);

        $this->robotsContent = $contents;

        $oneLineContents = str_replace(["\n", "\r"], '', $contents);

        if($oneLineContents === 'User-agent: *Disallow:') {
            $this->robotsStatus = false;
        } else {
            $this->robotsStatus = true;
        }
    }

    protected function checkSitemap()
    {
        $sitemapPath = public_path('sitemap.xml');

        if(!file_exists($sitemapPath)) { return $this->sitemapStatus = false; }

        $contents = file_get_contents($sitemapPath);

        $this->sitemapContent = $contents;

        $this->sitemapStatus = (empty($contents)) ? false : true;
    }

    public function generateSitemap()
    {
        SitemapGenerator::create(config('app.url'))->writeToFile(public_path('sitemap.xml'));
    }

    #[On('save-seo')]
    public function save()
    {
        $analyticsPath = resource_path('views/components/seo/analytics.blade.php');

        file_put_contents($analyticsPath, $this->analyticsContent);

        $robotsPath = public_path('robots.txt');

        file_put_contents($robotsPath, $this->robotsContent);

        $sitemapPath = public_path('sitemap.xml');

        file_put_contents($sitemapPath, $this->sitemapContent);
    }

    public function render()
    {
        return view('lux::livewire.seo.index');
    }
}