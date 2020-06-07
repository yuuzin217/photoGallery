<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use Storage;

class photoGalleryModel extends Model
{
    protected $table    = 'images_path';                        // テーブルを指定
    protected $fillable = ['user_id', 'original_name', 'path']; // ホワイトリスト

    /**
     * コミット処理
     *
     * @return bool
     */
    public function commit() {
        DB::commit();
        return true;
    }

    /**
     * ロールバック処理
     *
     * @return bool
     */
    public function rollback($e) {
        DB::rollback();
        Log::debug($e->getMessage());
        return false;
    }

    /**
     * 画像の取得
     *
     * @return array $imagesAllPath
     */
    public function getAllImages(): array
    {
        $view = $imagesAllPath = [];
        $search  = ["public", "\\"]; // パスを書き換えるのに利用
        $replace = ["storage", ""];  // パスを書き換えるのに利用
        $imagesAll = photoGalleryModel::select('id', 'original_name', 'path', 'delete_flg')
            ->where('delete_flg', 1)
            ->get();
        $view['delMode'] = DB::table('user_setting')
            ->select('delmode')
            ->where('user_id', 1)
            ->first()
            ->delmode;

        // DBから取得したimageパスを配列に格納
        if (isset($imagesAll)) {
            foreach ($imagesAll as $image) {
                $id = $image->id;
                $name = $image->original_name;
                $path = str_replace($search, $replace, $image->path);
                $imagesAllPath[] = [
                    'id'   => $id,
                    'name' => $name,
                    'path' => $path
                ];
            }
            $view['imagesAllPath'] = $imagesAllPath;
        }

        return $view;
    }

    /**
     * 画像の保存
     *
     * @param  array $images
     * @return bool
     */
    public function insertImages(array $images): bool
    {
        // 画像をDBに保存
        for ($i = 0; $i < count($images); $i++) {
            $imageName = $images[$i]->getClientOriginalName();
            $path = $images[$i]->store('public/photoGallery_images'); // 画像をimageフォルダに保存して、画像パス取得

            DB::beginTransaction();
            try {
                $save = photoGalleryModel::create([
                    'user_id'       => 1, // IDは仮で指定
                    'original_name' => $imageName,
                    'path'          => $path
                ]);
                DB::commit();
            } catch (\Exception $e) {
                Storage::delete($path);
                return $this->rollback($e);
            }
        }

        return true;
    }

    /**
     * 画像の削除
     *
     * @param string $id
     * @return bool
     */
    public function deleteImage(string $id): bool
    {
        $query = DB::table('user_setting')
            ->select('delmode')
            ->where('user_id', 1)
            ->first();

        if ($query->delmode === 1) {
            DB::beginTransaction();
            try {
                photoGalleryModel::where('id', $id)
                    ->update(['delete_flg' => 0]);
                return $this->commit();
            } catch (\Exception $e) {
                return $this->rollback($e);
            }
        } else {
            $image = photoGalleryModel::find($id);
            DB::beginTransaction();
            try {
                $image->delete(); // DBから特定の画像データを削除
                Storage::delete($image->path); // imageフォルダから画像を削除
                return $this->commit();
            } catch (\Exception $e) {
                return $this->rollback($e);
            }
        }
    }

    /**
     * 設定
     *
     * @param array $setData
     * @return array(bool) $check
     */
    public function setting(array $setData): array
    {
        $check = [];

        // 論理削除 or 物理削除
        DB::beginTransaction();
        try {
            DB::table('user_setting')
                ->where('user_id', 1) // IDは仮で指定
                ->update(['delmode' => $setData['delMode']]);
            $check['delmode'] = $this->commit();
        } catch (\Exception $e) {
            $check['delmode'] = $this->rollback($e);
        }

        // 全レコード物理削除
        if (isset($setData['allDelete'])) {
            $check['allDelete'] = $this->allDelete();
        }

        return $check;
    }

    /**
     * 画像データ全削除
     *
     * @return bool
     */
    public function allDelete(): bool
    {
        DB::beginTransaction();
        try {
            DB::table('images_path')->delete();
            Storage::deleteDirectory('public/photoGallery_images');
            return $this->commit();
        } catch (\Exception $e) {
            return $this->rollback($e);
        }
    }
}