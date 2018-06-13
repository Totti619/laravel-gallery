@section(config('gallery.views.index.section.0'))

    <ul>
        {{--{{ dd($folders ?? null) }}--}}
        {{--{{ dd($images ?? null) }}--}}
        @foreach($folders as $folder)
            <li class="folder">
                <a data-child-folder-count="{{ $folder->numberOf(Folder::class) }}" data-child-image-count="{{ $folder->numberOf(Image::class) }}" href="{{ $folder->alternateUrl() }}">
                    {{ $folder->getName() }}
                </a>
            </li>
        @endforeach
        @foreach($images as $image)
            <li class="image">
                <a href="{{ $image->url() }}">
                    <img {{ $image->metaToHTMLAttr() }} src="{{ $image->url() }}" alt="{{ $image->meta('alt') ?? $image->url() }}">
                </a>
            </li>
        @endforeach
    </ul>

@endsection

@section(config('gallery.views.index.section.1'))

@endsection