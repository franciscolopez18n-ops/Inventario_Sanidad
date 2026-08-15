@php
    use App\Constants\FlashType;

    $classes = [
        FlashType::SUCCESS => 'alert-success',
        FlashType::ERROR => 'alert-error',
        FlashType::WARNING => 'alert-warning',
        FlashType::INFO => 'alert-info'
    ];
@endphp

<div class="alerts-container">
    @foreach (FlashType::cases() as $type)
        @foreach((array)session($type, []) as $i => $message)
            <p class="alert {{ $classes[$type] }} hidden"> {{ $message }} </p>
        @endforeach
    @endforeach
</div>
