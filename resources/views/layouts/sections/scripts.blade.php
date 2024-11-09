@vite([
  'resources/assets/vendor/libs/jquery/jquery.js',
  'resources/assets/vendor/js/bootstrap.js',
  'resources/assets/vendor/js/menu.js'
])

@yield('vendor-script')

@vite(['resources/assets/js/main.js'])



@yield('page-script')


 
@livewireScripts
