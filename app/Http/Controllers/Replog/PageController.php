<?php

namespace App\Http\Controllers\Replog;

use App\Models\Page;
use Inertia\Inertia;

class PageController extends ReplogController
{
    const TENANT = 'replog';

    public function page(Page $page)
    {
        $this->setMetaTags($page);

        return Inertia::render('GenericContent', [
            'title' => $page->title,
            'intro' => $page->intro,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }

    public function about()
    {
        $page = new Page();
        $page = $page->getAboutContent(self::TENANT);

        $this->setMetaTags($page);

        return Inertia::render('GenericContent', [
            'title' => $page->title,
            'intro' => $page->intro,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }

    public function privacy(Page $page)
    {
        $page = $page->getPrivacyContent(self::TENANT);

        return Inertia::render('GenericContent', [
            'title' => $page->title,
            'intro' => $page->intro,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }

    public function cookie(Page $page)
    {
        $page = $page->getCookieContent(self::TENANT);
        $this->setMetaTags($page);

        return Inertia::render('GenericContent', [
            'title' => $page->title,
            'intro' => $page->intro,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }

    public function termsConditions(Page $page)
    {
        $page = $page->getTermsContent(self::TENANT);

        return Inertia::render('GenericContent', [
            'title' => $page->title,
            'intro' => $page->intro,
            'body' => $page->body,
            'flexible' => $page->flexible,

        ]);
    }

    public function participate(Page $page)
    {
        $page = $page->getParticipationContent(self::TENANT);

        return Inertia::render('GenericContent', [
            'title' => $page->title,
            'intro' => $page->intro,
            'body' => $page->body,
            'flexible' => $page->flexible,

        ]);
    }

    public function instructions(Page $page)
    {
        $page = $page->getInstructionsContent(self::TENANT);

        return Inertia::render('GenericContent', [
            'title' => $page->title,
            'intro' => $page->intro,
            'body' => $page->body,
            'flexible' => $page->flexible,
        ]);
    }
}
