@props(['value'])

<table {{ $attributes->merge(['class' => 'table table--shaded']) }}>
    @isset($dataTable['thead'])
        <thead class="table__head">
        <tr class="table__row">
            @foreach($dataTable['thead'] as $th)
                <td class="table__cell  @isset($th['class']){{ $th['class'] }}@endisset">
                    {!! $th['value'] !!}
                </td>
            @endforeach
            <td class="table__cell"></td>
            <td class="table__cell"></td>
        </tr>
        </thead>
    @endisset
    <tbody class="table__body">
        @isset($dataTable['tbody'])
            @foreach($dataTable['tbody'] as $tr)
                <tr class="table__row @if($tr['done'])text-done @endif">

                    @foreach($tr as $key => $td)
                        @if ($key !== 'done')
                            @if(isset($td['key']) && isset($td['name']))
                                <td @isset($td['attributes']){!! $td['attributes']  !!}@endisset class="table__cell table__cell--checkbox table__cell--no-wrap @isset($td['class']){{ $td['class'] }}@endisset">
                                    <input action="{{route('task.done')}}" name="{{$td['name']}}" value="{{$td['key']}}" type="checkbox" @if($tr['done'])checked @endif>'
                                </td>
                            @else
                                <td @isset($td['attributes']){!! $td['attributes']  !!}@endisset class="table__cell @isset($td['class']){{ $td['class'] }}@endisset">
                                    {!! $td['value'] !!}
                                </td>
                            @endif
                        @endif
                    @endforeach
                    <td class="table__cell table__cell--remainder table__cell--edit">
                        <x-primary-button
                            class="item_button_edit"
                            action="{{ route('task.update') }}"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'modal-create-task')"
                            title="Редактировать"
                        >
                            <svg  width="24" height="24" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" fill="white"><g id="Layer_32" data-name="Layer 32"><path d="m43.586 19-28.586 28.586v-1.586a1 1 0 0 0 -.684-.949l-1.465-.488 28.149-28.149zm-32.1 27.217 1.513.5v3.283a1 1 0 0 0 1 1h3.279l.5 1.513-6.445 3.411-3.258-3.262zm-4.386 8.294 2.39 2.389-5.09 2.7zm12.341-3.362-.489-1.465a1 1 0 0 0 -.952-.684h-1.586l28.586-28.586 2.586 2.586zm30.2-28.928-7.856-7.856 2.98-1.192 6.067 6.068zm2.606-4.394-6.067-6.068 1.191-2.98 7.856 7.856zm7.991-7.473-4.238 4.232-6.586-6.586 4.232-4.232a2.714 2.714 0 0 1 3.708 0l2.878 2.878a2.622 2.622 0 0 1 0 3.708z"/></g></svg>
                        </x-primary-button>
                        </td>
                    <td class="table__cell table__cell--remainder table__cell--del">
                        <x-danger-button
                            class="item_button_delete"
                            action="{{ route('task.delete') }}"
                            class="item_button_delete"
                            title="Удалить"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 64 64" fill="white">
                                <path d="M 28 6 C 25.791 6 24 7.791 24 10 L 24 12 L 23.599609 12 L 10 14 L 10 17 L 54 17 L 54 14 L 40.400391 12 L 40 12 L 40 10 C 40 7.791 38.209 6 36 6 L 28 6 z M 28 10 L 36 10 L 36 12 L 28 12 L 28 10 z M 12 19 L 14.701172 52.322266 C 14.869172 54.399266 16.605453 56 18.689453 56 L 45.3125 56 C 47.3965 56 49.129828 54.401219 49.298828 52.324219 L 51.923828 20 L 12 19 z M 20 26 C 21.105 26 22 26.895 22 28 L 22 51 L 19 51 L 18 28 C 18 26.895 18.895 26 20 26 z M 32 26 C 33.657 26 35 27.343 35 29 L 35 51 L 29 51 L 29 29 C 29 27.343 30.343 26 32 26 z M 44 26 C 45.105 26 46 26.895 46 28 L 45 51 L 42 51 L 42 28 C 42 26.895 42.895 26 44 26 z"></path>
                            </svg>
                        </x-danger-button>
                    </td>
                </tr>
            @endforeach
        @endisset
    </tbody>
</table>

