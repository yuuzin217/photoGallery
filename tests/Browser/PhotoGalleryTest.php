<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PhotoGalleryTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('フォトギャラリー')
                    ->assertSee('ホーム')
                    ->click('@setting')
                    ->assertSee('削除方式')
                    ->assertSee('リセット')
                    ->click('@home')
                    ->attach('@file', '/Users/yuuhibino/Desktop/header.jpg')
                    ->click('@upload')
                    ->assertSee('header.jpg')
                    ->click('.delete')
                    ->acceptDialog()
                    ->assertDontSee('<dd>header.jpg</dd>');
        });
    }
}
