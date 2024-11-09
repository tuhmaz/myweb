@php
use Illuminate\Support\Facades\Vite;
@endphp

 @vite(['resources/assets/vendor/js/helpers.js'])
 @if ($configData['hasCustomizer'])

  @vite(['resources/assets/vendor/js/template-customizer.js'])
@endif

   @vite(['resources/assets/js/front-config.js'])

 