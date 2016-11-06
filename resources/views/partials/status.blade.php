@if(Session::has('status'))
    <div class="alert alert-info">
        {{Session::get('status')}}
    </div>
@endif