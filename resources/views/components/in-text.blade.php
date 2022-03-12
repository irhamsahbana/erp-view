@props([
    'col' => 12,

    'label' => null,
    'id' => $id ? $id : $name,
    'name' => null,
    'type' => 'text',
    'placeholder' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'step' => null,
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
            value="{{ old($name) ?? $value ?? '' }}"
            @if($step) step="{{ $step }}" @endif
            @if($required) required @endif
            @if($disabled) disabled @endif>
    </div>
  </div>