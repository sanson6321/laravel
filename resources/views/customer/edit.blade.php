<div id="form-ajax" class="form-group">
    @csrf
    <input type="hidden" name="id" value="{{ $customer?->id }}" />
    <input type="hidden" name="updated_no" value="{{ $customer?->updated_no }}" />
    <div>
        <p>{{ '名前*' }}</p>
        <input type="text" name="name" value="{{ $customer?->name }}" />
    </div>
    <div>
        <p>{{ '郵便番号*' }}</p>
        <input type="text" name="post_code" value="{{ $customer?->post_code }}" />
    </div>
    <div>
        <p>{{ '都道府県*' }}</p>
        <select name="prefecture">
            @foreach (config('const.prefectures') as $key => $prefecture)
                <option value="{{ $key }}" @selected($key === $customer?->prefecture)>{{ $prefecture }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <p>{{ '市区町村*' }}</p>
        <input type="text" name="address" value="{{ $customer?->address }}" />
    </div>
    <div>
        <p>{{ '丁目・番地・号・建物名・部屋番号' }}</p>
        <input type="text" name="address_sub" value="{{ $customer?->address_sub }}" />
    </div>
    <div>
        <p>{{ '性別*' }}</p>
        @foreach (config('const.genders') as $key => $gender)
            <input type="radio" id="{{ $gender }}" name="gender" value="{{ $key }}"
                @checked($key === $customer?->gender) />
            <label for="{{ $gender }}">{{ $gender }}</label>
        @endforeach
    </div>
    <div>
        <p>{{ '備考' }}</p>
        <textarea name="note" rows="3">{{ $customer?->note }}</textarea>
    </div>
    <button type="submit" class="bg-blue">{{ '保存' }}</button>
</div>
