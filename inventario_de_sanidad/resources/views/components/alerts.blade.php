@php
    use App\Constants\AlertType;

    $classes = [
        AlertType::SUCCESS => 'alert-success',
        AlertType::ERROR => 'alert-error',
        AlertType::WARNING => 'alert-warning',
        AlertType::INFO => 'alert-info'
    ];
@endphp

<div class="alerts-container">
    @foreach (AlertType::cases() as $type)
        @foreach((array)session($type, []) as $i => $message)
            <p class="alert {{ $classes[$type] }} hidden"> {{ $message }} </p>
        @endforeach
    @endforeach
</div>
