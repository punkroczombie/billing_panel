<x-app-layout title="Redirecting to Terms of Service">
    <meta http-equiv="refresh" content="0; url=https://mythicaldimensions.me/tos/" />
    <style>
        body {
            background-image: url("{{ config('settings::theme:bg-url', '#') }}");
            background-size: center;
            background-repeat: no-repeat;
        }
    </style>
    <div>
        <div class="content ">
            <div class="content-box">
            <h1 class="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl dark:text-white">
                Redirecting to Terms of Service
            </h1>
            <div class="prose dark:prose-invert min-w-full">
                <p>If you have an older browser and your page did not redirect press this button below:</p>
                <br />
                <a class="button button-primary" href="https://mythicaldimensions.me/tos/">Redirect</a>
            </div>
        </div>
    </div>
</x-app-layout>
