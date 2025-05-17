@php
    use Illuminate\Support\Facades\Cache;

    $locale = app()->getLocale();
    $userId = auth()->id() ?? 'guest';
    $cacheKey = "footer_{$locale}";
    $cacheTTL = 60 * 60 * 24; // 24h
@endphp

@if (Cache::has($cacheKey) && !request()->has('refresh_cache'))
    {!! Cache::get($cacheKey) !!}
@else
    @php
        ob_start();
    @endphp
    <footer class="text-color text-center py-3">
        © {{date('Y')}} Find an Idiot Like You!
    </footer>
    @php
        $footer = ob_get_clean();
        Cache::put($cacheKey, $footer, $cacheTTL);
        echo $footer;
    @endphp
@endif
