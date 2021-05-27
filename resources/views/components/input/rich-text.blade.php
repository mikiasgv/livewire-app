@props(['initialValue' => ''])

<div
    class="rounded-md shadow-sm"
    wire:ignore
    {{ $attributes }}
    x-data
    @trix-blur="$dispatch('change', $event.target.value)"
>
    <input type="hidden" id="x" value="{{ $initialValue }}">
    <trix-editor input="x" class="bg-white form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"></trix-editor>
</div>
