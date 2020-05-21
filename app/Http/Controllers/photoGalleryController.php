<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PhotoGalleryController extends Controller
{
    /**
     * 画像の表示
     *
     * @return Response
     */
    public function index() {
        return view('photoGallery'); // テンプレートに返す
    }
}
