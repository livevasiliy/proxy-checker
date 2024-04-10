<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect"
          href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap"
          rel="stylesheet" />
    @vite('resources/js/app.js')
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-8">
            <h1>{{ __('translations.header') }}</h1>
            <form>
                @csrf
                <label for="proxies">{{ __('translations.label') }}</label>
                <x-textarea></x-textarea>
                <button type="submit"
                        class="btn btn-secondary"
                        id="check_proxy_btn"
                >{{ __('translations.label_btn_check') }}
                </button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
