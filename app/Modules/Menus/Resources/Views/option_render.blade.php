@foreach($data as $item)
    <option value="{{ $module }}/{{ $item->slug }}">{{ $item->name }}</option>
@endforeach