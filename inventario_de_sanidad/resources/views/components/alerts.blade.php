@php
    use App\Enums\FlashType;

    $classes = [
        FlashType::SUCCESS->value => 'alert alert-success',
        FlashType::ERROR->value => 'alert alert-error',
        FlashType::WARNING->value => 'alert alert-warning',
        FlashType::INFO->value => 'alert alert-info'
    ];
@endphp

@foreach (FlashType::cases() as $type)
    @foreach((array)session($type->value, []) as $i => $message)
        <p class="{{ $classes[$type->value] }}"> {{ $message }} </p>
    @endforeach
@endforeach