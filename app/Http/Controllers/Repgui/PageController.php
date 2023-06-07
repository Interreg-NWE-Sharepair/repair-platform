<?php

namespace App\Http\Controllers\Repgui;

use App\Models\Page;
use App\Models\RepairGuidanceFormLog;
use Artesaos\SEOTools\Facades\SEOMeta;

class PageController extends RepguiController
{
    const TENANT = 'repgui';

    /**
     * Sets all the meta tags for the page.
     */
    private function setMeta(Page $page): void
    {
        SEOMeta::setTitle($page->title)->setDescription($page->seo_description)->setKeywords($page->seo_keywords);
    }

    public function page(Page $page = null)
    {

        $this->setMeta($page);

        return view('repgui.pages.generic', [
            'title' => $page->title,
            'intro' => $page->intro,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }

    public function about(Page $page)
    {
        $page = $page->getAboutContent(self::TENANT);

        $this->setMeta($page);

        return view('repgui.pages.generic', [
            'title' => $page->title,
            'intro' => $page->intro,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }

    public function privacy(Page $page)
    {
        $page = $page->getPrivacyContent(self::TENANT);

        $this->setMeta($page);

        return view('repgui.pages.generic', [
            'title' => $page->title,
            'intro' => $page->intro,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }

    public function cookie(Page $page)
    {
        $page = $page->getCookieContent(self::TENANT);

        $this->setMeta($page);

        return view('repgui.pages.generic', [
            'title' => $page->title,
            'intro' => $page->intro,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }

    public function termsConditions(Page $page)
    {
        $page = $page->getTermsContent(self::TENANT);

        $this->setMeta($page);

        return view('repgui.pages.generic', [
            'title' => $page->title,
            'intro' => $page->intro,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }

    public function participate(Page $page)
    {
        $page = $page->getParticipationContent(self::TENANT);

        $this->setMeta($page);

        return view('repgui.pages.generic', [
            'title' => $page->title,
            'intro' => $page->intro,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }

    public function instructions(Page $page)
    {
        $page = $page->getInstructionsContent(self::TENANT);

        $this->setMeta($page);

        return view('repgui.pages.generic', [
            'title' => $page->title,
            'intro' => $page->intro,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }

    public function contribute(Page $page)
    {
        $page = $page->getContributeContent(self::TENANT);

        $this->setMeta($page);

        return view('repgui.pages.generic', [
            'title' => $page->title,
            'intro' => $page->intro,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }

    public function tips(Page $page)
    {
        $page = $page->getGeneralTipsContent(self::TENANT);

        $this->setMeta($page);

        return view('repgui.pages.generic', [
            'title' => $page->title,
            'intro' => $page->intro,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }

    public function recycle(Page $page)
    {
        $page = $page->getRecycleContent(self::TENANT);
        $uuid = request()->query('uuid');
        $repairGuidanceLog = null;
        if ($uuid) {
            $repairGuidanceLog = RepairGuidanceFormLog::query()->where('uuid', $uuid)->first();
        }

        $this->setMeta($page);

        return view('repgui.pages.generic', [
            'title' => $page->title,
            'intro' => $page->intro,
            'body' => $page->body,
            'flexible' => $page->flexible,
            'repairGuidanceLog' => $repairGuidanceLog,
        ]);
    }
}
