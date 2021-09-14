<?xml version="1.0" encoding="UTF-8"?>
<{{ $file }}>
@foreach($items as $item)
    <{{ $node }}>
    @foreach($item as $propertie => $value)
        <{{ $propertie }}>{!! htmlspecialchars( html_entity_decode( $value, ENT_COMPAT, 'UTF-8' ), ENT_QUOTES )  !!}</{{ $propertie }}>
    @endforeach
    </{{ $node }}>
@endforeach
</{{ $file }}>
