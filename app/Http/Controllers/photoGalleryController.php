<?php

namespace App\Http\Controllers;

use App\photoGalleryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $search        = ["public", "\\"];                               // パスを書き換えるのに利用
        $replace       = ["storage", ""];                                // パスを書き換えるのに利用
        $imagesAll     = photoGalleryModel::select('path', 'id')->get(); // DBから画像情報取得
        $countImages   = count($imagesAll);                              // 画像数を取得
        $imagesAllPath = [];                                             // すべてのimageパスを格納

        // DBから取得したimageパスを配列に格納
        foreach ($imagesAll as $image) {
            $id = $image->id;                                     // idを取得
            $path = str_replace($search, $replace, $image->path); // 画像パスを書き換えて取得
            $imagesAllPath[] = [                                  // idとパスを配列に格納
                'id' => $id,
                'path' => $path
            ];
        }

        return view('photoGallery', compact('imagesAllPath', 'countImages')); // テンプレートに返す
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

    /**
     * 画像の削除
     *
     * @param $id
     * @return Response
     */
    public function delete($id)
    {
        $image = photoGalleryModel::find($id); // idから特定のレコードを抽出
        Storage::delete($image->path);         // imageフォルダから画像を削除
        $image->delete();                      // DBから特定の画像データを削除

        return redirect('/')->with('success', '画像を削除しました'); // 成功メッセージ
    }
}
