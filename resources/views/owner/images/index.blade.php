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
                    <x-flash-message status="{{ session('status') }}" />
                        {{-- フラッシュメッセージとボタンの幅がないので、調整 フラッシュメッセージはcomponentなので、componentの方で作成 --}}

                    <div class="flex justify-end mb-4">
                        <button onclick="location.href='{{ route('owner.images.create') }}'" class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">新規登録する</button>
                    </div>

                    <div class="flex flex-wrap">
                        {{-- 横並びにする wrapとすると、はみ出した分を改行する --}}
                        @foreach ($images as $image)
                            <div class="w-1/4 p-2 md:p-4 flex">
                                {{-- ブラウザ対応の時に画像が小さ過ぎて、paddingをかけ過ぎている。ただ、paddingがないと、ぴったりくっついてしまう。 --}}
                                <a href="{{ route('owner.images.edit', ['image' => $image->id]) }}">
                                    <div class="border rounded-md p-2 md:p-4">
                                        {{-- このコードを他でも使うので、コンポネント化する。 --}}

                                        {{-- <x-shop-thumbnail :filename="$shop->filename" /> 今回フォルダを変えるために名前を変更する --}}
                                            {{-- shop-thumbnail を thumbnail と変更する --}}
                                        <x-thumbnail :filename="$image->filename" type="products"/>
                                            {{-- Undefined variable $shop $shop->filenameではなく$image->filename --}}


                                        <div class="text-gray-700">{{ $image->title }}</div>
                                        {{-- タイトルはサムネイルの下に貼り付ける --}}
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    {{ $images->links(); }}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

