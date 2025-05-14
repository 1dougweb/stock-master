@props(['for' => null, 'messages' => null])

@if ($for)
    @error($for)
        <p {{ $attributes->merge(['class' => 'text-sm text-red-600']) }}>{{ $message }}</p>
    @enderror
@elseif ($messages)
    @foreach ((array) $messages as $message)
        <p {{ $attributes->merge(['class' => 'text-sm text-red-600']) }}>{{ $message }}</p>
    @endforeach
@endif
