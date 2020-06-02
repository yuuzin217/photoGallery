@extends('layouts.layout')
@section('title', 'フォトギャラリー')
@section('css')
    <link href="{{ asset('css/photoGallery.css') }}" rel="stylesheet">
    <link href="{{ asset('lightbox2/lightbox.min.css') }}" rel="stylesheet">
@endsection
@section('content')

    {{-- 設定タブ --}}
    <ul class="menu">
        <li><a href="#" class="active" data-id="home">ホーム</a></li>
        <li><a href="#" data-id="setting">設定</a></li>
    </ul>

    <div id="photomain">

        {{-- エラーメッセージ --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 成功メッセージ --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- 画像一覧表示 --}}
        <div class="container active" id="home">
            @foreach ($imagesAllPath as $image)
                <dl class="img">
                    <dd>
                        <a href="{{ $image['path'] }}" data-lightbox="images" data-title="{{ $image['name'] }}">
                            <img src="{{ $image['path'] }}" alt="{{ $image['name'] }}">
                        </a>
                    </dd>
                    <dd>
                        {{ $image['name'] }}
                    </dd>
                    <dd class="delete" data-id="{{ $image['id'] }}">
                        削除
                    </dd>
                </dl>
                <form id="f_{{ $image['id'] }}" method="post" action="/{{ $image['id'] }}">
                    @csrf
                    {{ method_field('DELETE') }}
                </form>
            @endforeach
            @for ($i = 0; $i < 3; $i++)
                <dl class="img">
                </dl>
            @endfor

        </div>

        <div class="container" id="setting">
            <form method="POST" action="/setting">
                <ul class="setting">
                    <li>
                        <h3>削除方式</h3>
                        <input type="radio" name="delMethod" value="softDel" checked="checked">ソフトデリート
                        <input type="radio" name="delMethod" value="hardDel">ハードデリート
                    </li>
                    <hr>
                    <li>
                        <h3>リセット</h3>
                        <p>全ての情報を削除します。</p>
                        完全削除<input type="checkbox" name="allDelete" value="tes">
                    </li>
                </ul>
                @csrf
                <input type="submit" value="設定を確定">
            </form>
        </div>

        {{-- 画像アップロードフォーム --}}
        <form class="active" id="uploadForm" method="POST" action="" enctype="multipart/form-data">
            <input type="file" multiple name="images[]">
            @csrf
            <input type="submit" value="アップロード">
        </form>

    </div>
@endsection
@section('js')
    <script src="{{ asset('lightbox2/lightbox.min.js') }}"></script>
    <script src="{{ asset('js/photoGallery.js') }}"></script>
@endsection