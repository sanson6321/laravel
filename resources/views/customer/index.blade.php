@extends('layout.base')
@section('title', '顧客一覧')
@section('css')
    <link rel="stylesheet" href="{{ FormatAsset::asset('css/customer.css') }}">
@endsection
@section('content')
    <h1>{{ '顧客一覧' }}</h1>
    <div class="customer-table">
        <form action="{{ route('customer') }}" method="GET">
            <div class="form-search">
                <div>
                    <p>{{ '名前' }}</p>
                    <input type="text" name="name" value="{{ request()->query('name') }}" />
                </div>
                <button type="submit" class="bg-blue">{{ '検索' }}</button>
            </div>
        </form>
        <div class="customer-create">
            <button type="button" class="bg-green modal-open">{{ '新規作成' }}</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>
                        @php
                            [$active, $order, $parameters] = FormatOrder::get('id');
                        @endphp
                        <a href="{{ route('customer', $parameters) }}">
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
                        {{ 'メールアドレス' }}
                    </th>
                    <th>
                        @php
                            [$active, $order, $parameters] = FormatOrder::get('updated_at');
                        @endphp
                        <a href="{{ route('customer', $parameters) }}">
                            <span>{{ '更新日時' }}</span>
                            <i @class([
                                'fa-solid',
                                'fa-arrow-up' => $active && str_ends_with($order, 'desc'),
                                'fa-arrow-down' => $active && str_ends_with($order, 'asc'),
                                'fa-arrows-up-down' => $active === false,
                            ])></i>
                        </a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customerList as $customer)
                    <tr>
                        <td class="text-center">
                            <a class="modal-open" data-id={{ $customer->id }}>
                                {{ $customer->id }}
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </td>
                        <td class="text-center">{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td class="text-center">{{ $customer->updated_at->format('Y/m/d H:i') }}</td>
                        <td>
                            <div class="customer-detail">
                                <a class="customer-detail-show">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </a>
                                <div class="customer-detail-list">
                                    <div>
                                        <a class="password-modal-open" data-id={{ $customer->id }}
                                            data-name={{ $customer->name }}>
                                            {{ 'パスワード変更' }}
                                        </a>
                                        <a class="delete-modal-open" data-id={{ $customer->id }}
                                            data-name="{{ $customer->name }}">
                                            {{ '削除' }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
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
    <div id="delete" class="modal">
        <div class="modal-layout">
            <div class="modal-content">
                <div class="modal-title">
                    <p id="delete-name"></p>
                    <a class="modal-close">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
                <div class="modal-body">
                    <p>{{ '削除してもよろしいですか' }}</p>
                    <form id="form-delete">
                        <input type="hidden" name="id">
                        <div class="button-group">
                            <button type="button" class="bg-gray modal-close">{{ 'キャンセル' }}</button>
                            <button type="submit" class="bg-red">{{ '削除' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(function() {
            // 編集モーダル開く
            $('.modal-open').on('click', function() {
                $('#form-edit').empty();
                const deferred = ajaxRender("{{ route('customer.edit') }}", {
                    id: $(this).data('id')
                });
                deferred.done(function(data) {
                    openModal('edit');
                    $('#form-edit').html(data);
                });
            });
            // 編集実行
            $('#form-edit').submit(function() {
                const deferred = ajaxAction("{{ route('customer.upsert') }}", $(this).serialize());
                deferred.done(function() {
                    location.reload();
                });
                return false;
            });
            // 詳細開く
            $('.customer-detail-show').on('click', function() {
                $(this).next().show();
                setTimeout(() => {
                    // 別のクリックイベントで同時に発火されるのを防止
                    document.addEventListener('click', () => {
                        // 非表示
                        $(this).next().hide();
                    }, {
                        once: true
                    });
                }, 100)
            });
            // 削除モーダル開く
            $('.delete-modal-open').on('click', function() {
                $('#form-delete > input[name="id"]').val($(this).data('id'));
                $("#delete-name").text($(this).data('name'));
                openModal('delete');
            });
            // 削除実行
            $('#form-delete').submit(function() {
                const deferred = ajaxAction("{{ route('customer.delete') }}", $(this).serialize());
                deferred.done(function() {
                    location.reload();
                });
                return false;
            })
        })
    </script>
@endsection
