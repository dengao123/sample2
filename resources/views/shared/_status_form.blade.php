<form action="{{ route('statuses.store') }}" method="post">
    {{csrf_field()}}
    @include('shared._errors')
    <textarea name="content" rows="3" class="form-control">
        {{ old('content') }}
    </textarea>
    <button type="submit" class="btn btn-primary pull-right">发布</button>
</form>