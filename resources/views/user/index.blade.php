@extends('layout.base')
@section('title', 'ユーザ')
@section('css')
    <link rel="stylesheet" href="{{ FormatAsset::asset('css/user.css') }}">
@endsection
@section('content')
    <h1>{{ 'ユーザ' }}</h1>
    <div class="user-table">
        <form action="{{ route('user') }}" method="GET">
            <div class="search-form">
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
                        <td class="text-center">{{ $user->id }}</td>
                        <td class="text-center">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
