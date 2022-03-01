@php
    $hash = \Str::random(4);
@endphp

@props([
    'col' => 12,

    'label' => null,
    'id' => $id ? $id : $name,
    'name' => $hash,
    'type' => 'text',
    'placeholder' => null,
    'value' => null,
    'required' => false,
])

<div {{ $attributes->merge([ 'class' => 'form-group col-sm-'.$col ]) }}>
    <label for="{{ $id }}">
        {{ $label }}
        @if($required) <span class="text-red">*</span> @endif
    </label>

    <div class="input-group">
        <input
            class="form-control"
            id="{{ $id }}"
            type="{{ $type }}"
            placeholder="{{ $placeholder }}"
            name="{{ $name }}"
            value="{{ old($name) ? old($name) : $value ? $value : '' }}"
            @if($required) required @endif>
    </div>
  </div>