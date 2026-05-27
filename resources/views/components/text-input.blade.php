@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-black :border-black focus:ring-black :ring-black rounded-md shadow-sm']) }}>




