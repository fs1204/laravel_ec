{{-- shops/index.blade.php をコピーして貼り付ける --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- ログインに成功したら、$shopsという変数が渡ってくるので、foreach で書くことにする --}}
                    <x-flash-message status="{{ session('status') }}" />
                    {{-- // フラッシュメッセージを表示するタグを追加する必要がある
                    // resources/views/admin/owners/index.blade.php から 以下をコピー
                    // <x-flash-message status="{{ session('status') }}" /> --}}


                    {{-- 新規作成のボタンがない。admin/owners/index.blade.php の中からコピーする。 --}}
                    <div class="flex justify-end mb-4">
                        <button onclick="location.href='{{ route('owner.images.create') }}'" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">新規登録する</button>
                    </div>

                    @foreach ($images as $image)
                        <div class="w-1/4 p-4">
                            <a href="{{ route('owner.images.edit', ['image' => $image->id]) }}">
                                <div class="borde rounded-md p-4">
                                    {{-- このコードを他でも使うので、コンポネント化する。 --}}
                                    <div class="text-xl">{{ $image->title }}</div>
                                    {{-- <x-shop-thumbnail :filename="$shop->filename" /> 今回フォルダを変えるために名前を変更する --}}
                                        {{-- shop-thumbnail を thumbnail と変更する --}}
                                    <x-thumbnail :filename="$shop->filename" type="products"/>
                                </div>
                            </a>
                        </div>
                    @endforeach

                    {{ $images->links(); }}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

