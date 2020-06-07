<?php

namespace App\Http\Controllers;

use App\PhotoGalleryModel;
use Illuminate\Http\Request;
use App\Http\Requests\PhotoGalleryRequest;
use Auth;

class PhotoGalleryController extends Controller
{
    /**
     * 画像の表示
     *
     * @return Response
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        // if (Auth::check())
        $model = new PhotoGalleryModel;
        $view = $model->getAllImages();
        return view('photoGallery', compact('view'));
    }

    /**
     * 画像の保存
     *
     * @param $request
     * @return Response
     */
    public function store(PhotoGalleryRequest $request)
    {
        $images = $request->images;
        $model = new PhotoGalleryModel;
        $result = $model->insertImages($images);
        if ($result) {
            return redirect('/')->with('success', '画像をアップロードしました。');
        } else {
            return redirect('/')->with('error', '画像のアップロードに失敗しました。');
        }
    }

    /**
     * 画像の削除
     *
     * @param string $id
     * @return Response
     */
    public function delete(string $id)
    {
        $model = new PhotoGalleryModel;
        if ($model->deleteImage($id)) {
            return redirect('/')->with('success', '画像を削除しました。');
        }
        return redirect('/')->with('error', '画像の削除に失敗しました。');
    }

    /**
     * 設定
     *
     * @param $request
     * @param string $id
     */
    public function setting(Request $request)
    {
        $model = new PhotoGalleryModel;
        $check = $model->setting($request->all());
        if (isset($check['allDelete']) && ($check['delmode'] && $check['allDelete'])) {
            return redirect('/')->with('success', "設定しました。すべての画像を削除しました");
        } elseif ($check['delmode']) {
            return redirect('/')->with('success', '設定しました');
        } else {
            return redirect('/')->with('error', '設定に失敗しました');
        }
    }
}
