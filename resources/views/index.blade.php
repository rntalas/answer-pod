<x-layout :title="__('app.title')">
    <p class="mb-4">
        <span class="text-xl mb-4">@lang('app.welcome')</span><br>
        <span class="text-lg">@lang('app.description')</span>
    </p>
    @if ($subjects->count() > 0)
        <p>@lang('app.subjects.found')</p>

        <ul class="list-inside list-disc mb-4">
            @foreach ($subjects as $subject)
                <li>
                    <a href="{{ url('subject/' . $subject->id) }}" class="link">
                        {{ $subject->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p>@lang('app.subjects.!found')</p>
    @endif

    <p>@lang('app.subjects.create')</p>
</x-layout>
