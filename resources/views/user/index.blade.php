@extends('layout.base')
@section('title', 'ユーザ一覧')
@section('css')
    <link rel="stylesheet" href="{{ FormatAsset::asset('css/user.css') }}">
@endsection
@section('content')
    <h1>{{ 'ユーザ一覧' }}</h1>
    <div class="user-table">
        <form action="{{ route('user') }}" method="GET">
            <div class="form-search">
                <div>
                    <p>{{ '名前' }}</p>
                    <input type="text" name="name" value="{{ old('name') }}" />
                </div>
                <button type="submit" class="bg-blue">{{ '検索' }}</button>
            </div>
        </form>
        <table>
            <thead>
                <tr>
                    <th>
                        @php
                            [$active, $order, $parameters] = FormatOrder::get('id');
                        @endphp
                        <a href="{{ route('user', $parameters) }}">
                            <span>{{ 'ID' }}</span>
                            <i @class([
                                'fa-solid',
                                'fa-arrow-up' => $active && str_ends_with($order, 'desc'),
                                'fa-arrow-down' => $active && str_ends_with($order, 'asc'),
                                'fa-arrows-up-down' => $active === false,
                            ])></i>
                        </a>
                    </th>
                    <th>
                        {{ '名前' }}
                    </th>
                    <th>
                        @php
                            [$active, $order, $parameters] = FormatOrder::get('email');
                        @endphp
                        <a href="{{ route('user', $parameters) }}">
                            <span>{{ 'メールアドレス' }}</span>
                            <i @class([
                                'fa-solid',
                                'fa-arrow-up' => $active && str_ends_with($order, 'desc'),
                                'fa-arrow-down' => $active && str_ends_with($order, 'asc'),
                                'fa-arrows-up-down' => $active === false,
                            ])></i>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($userList as $user)
                    <tr>
                        <td class="text-center">
                            <a class="modal-open" data-id={{ $user->id }}>
                                {{ $user->id }}
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                        <td class="text-center">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="edit" class="modal">
        <div class="modal-layout">
            <div class="modal-content">
                <div class="modal-title">
                    <p>{{ 'ユーザ編集' }}</p>
                    <a class="modal-close">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="modal-body">
                    <form id="form-edit"></form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(function() {
            $('.modal-open').on('click', function() {
                $('#form-edit').empty();
                const deferred = ajaxRender("{{ route('user.edit') }}", {
                    id: $(this).data('id')
                });
                deferred.done(function(data) {
                    openModal('edit');
                    $('#form-edit').html(data);
                });
            });
            $('.modal-close').on('click', function() {
                closeModal('edit');
            });
            $('#form-edit').submit(function() {
                $(this).addClass('form-loading');
                const deferred = ajaxAction("{{ route('user.upsert') }}", $(this).serialize());
                deferred.done(function(data) {
                    console.log(data);
                });
                return false;
            });
        })
    </script>
@endsection
