@props([
    'title',    //初期値を設定しなくてもよい
    'message' => '初期値です。',
    'content' => '本文初期値です'
])
<div {{ $attributes->merge(['class' => 'border-2 shadow-md w-1/4 p-2',]) }}>
    {{-- データの属性のデフォルトが設定される。使う側からデータが渡されると基本は上書きされるがclassの場合だけ、結合される --}}
    <div>{{ $title }}</div>
    <div>画像</div>
    <div>{{ $content }}</div>
    <div>{{ $message }}</div>
</div>

