<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class photoGalleryModel extends Model
{
    protected $table    = 'images_path';                        // テーブルを指定
    protected $fillable = ['user_id', 'original_name', 'path']; // ホワイトリスト

    /**
     * 画像の取得
     *
     * @return array $imagesAllPath
     */
    public function getAllImages(): array
    {
        $imagesAllPath = [];
        $search  = ["public", "\\"]; // パスを書き換えるのに利用
        $replace = ["storage", ""];  // パスを書き換えるのに利用
        $imagesAll = photoGalleryModel::select('id', 'original_name', 'path', 'delete_flg')->get();

        // DBから取得したimageパスを配列に格納
        if (isset($imagesAll)) {
            foreach ($imagesAll as $image) {
                if ($image->delete_flg === 1) {
                    $id = $image->id;
                    $name = $image->original_name;
                    $path = str_replace($search, $replace, $image->path);
                    $imagesAllPath[] = [
                        'id'   => $id,
                        'name' => $name,
                        'path' => $path,
                    ];
                }
            }
        }

        return $imagesAllPath;
    }

    /**
     * 画像の保存
     *
     * @param  array
     */
    public function insertImages(array $images)
    {
        // 画像をDBに保存
        for ($i = 0; $i < count($images); $i++) {
            $imageName = $images[$i]->getClientOriginalName();
            $path = $images[$i]->store('public/photoGallery_images'); // 画像をimageフォルダに保存して、画像パス取得

            // DBに値を保存
            DB::transaction(function () use ($imageName, $path) {
                $save = photoGalleryModel::create([
                    'user_id'       => 1, // IDは仮で指定
                    'original_name' => $imageName,
                    'path'          => $path
                ]);
            });
        }
    }

    /**
     * 画像の削除
     *
     * @param string $id
     */
    public function deleteImage(string $id)
    {
        $query = DB::table('user_setting')
            ->select('delmode')
            ->where('user_id', 1)
            ->first();
        if ($query->delmode === 1) {
            DB::transaction(function () use ($id) {
                photoGalleryModel::where('id', $id)->update(['delete_flg' => 0]);
            });
        } else {
            $image = photoGalleryModel::find($id);
            Storage::delete($image->path);             // imageフォルダから画像を削除
            DB::transaction(function () use ($image) { // DBから特定の画像データを削除
                $image->delete();
            });
        }
    }

    /**
     * 設定
     *
     * @param array $setData
     */
    public function setting(array $setData)
    {
        // 論理削除 or 物理削除
        DB::table('user_setting')->insert([
            'user_id' => 1, // IDは仮で指定
            'delmode' => $setData['delMode']
        ]);

        // 全レコード物理削除
        if (isset($setData['allDelete'])) {
            Storage::deleteDirectory('public/photoGallery_images');
            DB::transaction(function () {
                DB::table('images_path')->delete();
            });
        }
    }
}
