<div id="form-ajax" class="form-group">
    @csrf
    <input type="hidden" name="id" value="{{ $user?->id }}">
    <input type="hidden" name="updated_no" value="{{ $user?->updated_no }}">
    <div>
        <p>{{ '名前' }}</p>
        <input type="text" name="name" value="{{ $user?->name }}">
    </div>
    <div>
        <p>{{ 'メールアドレス' }}</p>
        <input type="email" name="email" value="{{ $user?->email }}" required>
    </div>
    <button type="submit" class="bg-blue">{{ '保存' }}</button>
</div>
