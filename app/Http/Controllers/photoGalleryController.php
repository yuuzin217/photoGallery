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
        $imagesAllPath = [];
        $search  = ["public", "\\"];                  // パスを書き換えるのに利用
        $replace = ["storage", ""];                   // パスを書き換えるのに利用
        $imagesAll = photoGalleryModel::select('id', 'original_name', 'path')->get();

        // DBから取得したimageパスを配列に格納
        foreach ($imagesAll as $image) {
            $id = $image->id;
            $name = $image->original_name;
            $path = str_replace($search, $replace, $image->path);
            $imagesAllPath[] = [
                'id'   => $id,
                'name' => $name,
                'path' => $path,
            ];
        }

        return view('photoGallery', compact('imagesAllPath'));
    }

    /**
     * 画像の保存
     *
     * @return Response
     */
    public function store(PhotoGalleryRequest $request)
    {
        $photos = $request->photos;

        // 画像をDBに保存
        for ($i = 0; $i < count($photos); $i++) {
            $photoName = $request->photos[$i]->getClientOriginalName();
            $path = $request->photos[$i]->store('public/photoGallery_images'); // 画像をimageフォルダに保存して、画像パス取得

            // DBに値を保存
            DB::transaction(function () use ($photoName, $path) {
                $save = photoGalleryModel::create([
                    'user_id'       => 1, // IDは仮で指定
                    'original_name' => $photoName,
                    'path'          => $path
                ]);
            });
        }

        return redirect('/')->with('success', '画像をアップロードしました。');
    }

    /**
     * 画像の削除
     *
     * @param $id
     * @return Response
     */
    public function delete($id)
    {
        $image = photoGalleryModel::find($id);
        Storage::delete($image->path);             // imageフォルダから画像を削除
        DB::transaction(function () use ($image) { // DBから特定の画像データを削除
            $image->delete();
        });

        return redirect('/')->with('success', '画像を削除しました');
    }
}
