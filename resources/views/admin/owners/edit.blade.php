{{-- create.blade.php を コピー&ペースト して 編集する --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            オーナー情報編集
        </h2>
    </x-slot>

    {{-- バリデーションでエラーが出た場合に、エラ〜メッセージを出したい
        laravel breeze の auth/register.blade.php の
                <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        の部分を使う--}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <section class="text-gray-600 body-font relative">
                        <div class="container px-5 mx-auto">
                            <div class="lg:w-1/2 md:w-2/3 mx-auto">
                                <div class="flex flex-col text-center w-full mb-12">
                                    <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">オーナー情報編集</h1>
                                </div>
                                <x-auth-validation-errors class="mb-4" :errors="$errors" />
                                {{-- <form action="{{ route('admin.owners.store') }}" method="post">  今回はstoreではなくupdate --}}
                                <form action="{{ route('admin.owners.update', ['owner' => $owner->id]) }}" method="post">
                                    @method('PUT')
                                    @csrf
                                    <div class="-m-2">
                                        <div class="p-2 w-1/2 mx-auto">
                                            <div class="relative">
                                                <label for="name" class="leading-7 text-sm text-gray-600">オーナー名</label>
                                                {{-- 現在入っている値を表示したいので、old('name') ではない --}}
                                                <input type="text" id="name" name="name" value="{{ $owner->name }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                {{-- requiredで必須となる --}}
                                            </div>
                                        </div>
                                        <div class="p-2 w-1/2 mx-auto">
                                            <div class="relative">
                                                <label for="email" class="leading-7 text-sm text-gray-600">メールアドレス</label>
                                                                                    {{-- 現在の値を入れる --}}
                                                <input type="email" id="email" name="email" value="{{ $owner->email }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                {{-- requiredで必須となる --}}
                                            </div>
                                        </div>
                                        {{-- emailの下に追加 --}}
                                        <div class="p-2 w-1/2 mx-auto">
                                            <div class="relative">
                                                <label for="shop" class="leading-7 text-sm text-gray-600">店名</label>
                                                {{-- <input type="email" id="email" name="email" value="{{ $owner->email }}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out"> --}}
                                                {{-- adminは店名を変えれないということで、divタグで書いていく --}}
                                                <div id="shop" class="w-full bg-gray-100 bg-opacity-50 rounded focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">{{ $owner->shop->name }}</div>
                                            </div>
                                        </div>
                                        <div class="p-2 w-1/2 mx-auto">
                                            <div class="relative">
                                                <label for="password" class="leading-7 text-sm text-gray-600">パスワード</label>
                                                <input type="password" id="password" name="password" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                {{-- requiredで必須となる --}}
                                            </div>
                                        </div>
                                        <div class="p-2 w-1/2 mx-auto">
                                            <div class="relative">
                                                {{-- laravel breeze をインストールした際のauthの中のregister.blade.php と合わせる形にしている --}}
                                                <label for="password_confirmation" class="leading-7 text-sm text-gray-600">パスワード確認</label>
                                                <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                {{-- requiredで必須となる --}}
                                            </div>
                                        </div>
                                        <div class="p-2 w-full flex justify-around mt-4">
                                                    {{-- 単純なリンクでpostするわけではないので、type="button" としておく --}}
                                            <button type="button" onclick="location.href='{{ route('admin.owners.index') }}'" class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">戻る</button>
                                            <button type="submit" class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">更新する</button>
                                            {{-- aタグで作ることもできるが、htmlの仕様的には良くない
                                                onclick という属性をつけるのが正しい --}}
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
