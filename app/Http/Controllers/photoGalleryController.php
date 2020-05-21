<?php

namespace App\Http\Controllers;

use App\photoGalleryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PhotoGalleryRequest;

class PhotoGalleryController extends Controller
{
    /**
     * 画像の表示
     *
     * @return Response
     */
    public function index()
    {
        return view('photoGallery'); // テンプレートに返す
    }

    /**
     * 画像の保存
     *
     * @return Response
     */
    public function store(PhotoGalleryRequest $request)
    {
        $photos = $request->photos; // リクエストから画像データを取得

        // 画像をDBに保存
        for ($i = 0; $i < count($photos); $i++) {
            $photoName = $request->photos[$i]->getClientOriginalName();        // 画像ファイル名を取得
            $path = $request->photos[$i]->store('public/photoGallery_images'); // 画像をimageフォルダに保存して、画像パス取得
            DB::transaction(function () use ($photoName, $path) {              // トランザクション追加
                $save = photoGalleryModel::create([                            // DBに値を保存
                    'user_id'       => 1, // IDは仮で指定
                    'original_name' => $photoName,
                    'path'          => $path
                ]);
            });
        }

        return redirect('/')->with('success', '画像をアップロードしました。'); // 成功メッセージ
    }
}
