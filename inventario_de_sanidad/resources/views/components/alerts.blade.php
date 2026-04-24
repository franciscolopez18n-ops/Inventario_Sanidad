@php
    use App\Constants\FlashType;

    $classes = [
        FlashType::SUCCESS => 'alert alert-success',
        FlashType::ERROR => 'alert alert-error',
        FlashType::WARNING => 'alert alert-warning',
        FlashType::INFO => 'alert alert-info'
    ];
@endphp

<div class="alerts-container">
    @foreach (FlashType::cases() as $type)
        @foreach((array)session($type, []) as $i => $message)
            <p class="{{ $classes[$type] }}"> {{ $message }} </p>
        @endforeach
    @endforeach
</div>