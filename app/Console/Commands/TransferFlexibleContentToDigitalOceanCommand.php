<?php

namespace App\Console\Commands;

use App\Models\Page;
use Exception;
use Illuminate\Console\Command;

class TransferFlexibleContentToDigitalOceanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flexible:image-transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'transfer local storage images from Flexible content module to Digital ocean';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \JsonException
     */
    public function handle()
    {
        try {
            $pages = Page::query()->whereNotNull('content')->get();

            if ($pages) {
                foreach ($pages as $page) {
                    $this->output->writeln('Fixing page with ID: ' . $page->id);
                    /** @var Page $page */
                    $content = json_decode($page->content, true);
                    if (empty($content)) {
                        continue;
                    }

                    foreach ($content as $index => $item) {

                        $this->output->writeln($item['layout']);
                        if (in_array($item['layout'], [
                            'image',
                            'wysiwyg-image',
                        ])) {
                            $image = $item['attributes']['image'];

                            $page->addMediaFromDisk($image, 'public')
                                 ->withCustomProperties(['flexibleKey' => $item['key']])
                                 ->toMediaCollection('image', 'digitalocean');

                            if (isset($item['attributes']['image'])) {
                                unset($item['attributes']['image']);

                                $content[$index] = $item;

                                $page->content = json_encode($content, JSON_THROW_ON_ERROR);
                                $page->save();
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->output->writeln($e->getMessage());
        }

        return 0;
    }
}
