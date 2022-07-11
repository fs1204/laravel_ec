<div>
    {{-- @if(empty($shop->filename)) --}}
    @if(empty($filename))
        <img src="{{ asset('images/no_image.jpg') }}">
    @else
    {{-- アップロードした画像はstorageフォルダに入っていく --}}
        {{-- <img src="{{ asset('storage/shops/' . $shop->filename) }}"> --}}
        <img src="{{ asset('storage/shops/' . $filename) }}">
        {{-- // shopsというフォルダを作ってその中に保存していく --}}
    @endif
</div>
