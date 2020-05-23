@extends('layouts.layout')
@section('title', 'フォトギャラリー')
@section('css')
    <link href="{{ asset('css/photoGallery.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div id="photomain">

        <!-- 成功メッセージ-->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- 画像一覧表示 -->
        <table id="tbl">
            @foreach ($imagesAllPath as $image)
                <div class="img">
                    <img src="{{ $image['path'] }}" width="20%" height="20%">
                    <form id="deleteForm" method="post" action="/{{ $image['id'] }}">
                        @csrf
                        <input type="submit" value="削除">
                    </form>
                </div>
            @endforeach
        </table>

        <!-- 画像アップロードフォーム -->
        <form id="uploadForm" method="POST" action="" enctype="multipart/form-data">
            <input type="file" multiple name="photos[]">
            @csrf
            <input type="submit" value="アップロード">
        </form>

    </div>
@endsection
@section('js')
    <script src="{{ asset('js/photoGallery.js') }}"></script>
@endsection