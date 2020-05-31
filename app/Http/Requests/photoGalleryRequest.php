<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class photoGalleryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /**
         * required
         * フィールドが入力データに存在しており、かつ空でないことをバリデートする。
         * フィールドは以下の条件の場合、「空」であると判断される。
         * ・値がnullである。
         * ・値が空文字列である。
         * ・値が空の配列か、空のCountableオブジェクトである。
         * ・値がパスのないアップロード済みファイルである。
         *
         * file
         * フィールドがアップロードに成功したファイルであることをバリデートします。
         *
         * image
         * フィールドで指定されたファイルが画像(jpg、png、bmp、gif、svg)であることをバリデートします。
         *
         * mimes:foo,bar,…
         * フィールドで指定されたファイルが拡張子のリストの中のMIMEタイプのどれかと一致することをバリデートします。
         *
         * max:値
         * フィールドが最大値として指定された値以下であることをバリデートします。
         * sizeルールと同様の判定方法で、文字列、数値、配列、ファイルが評価されます。
         */
        return [
            'images'   => 'required',
            'images.*' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }
}
