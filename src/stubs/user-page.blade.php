<!doctype html>
<html lang="vi">
<body>

<a href="{{$config->url}}">
    <h1>User's list</h1>
</a>

{{-- ./begin add --}}
@php
    if(request()->has('add')){
        $data = request()->all();
        $resAdd = $http->post('/users', $data)->json();
    }
@endphp
<form style="border: red solid 1px; width: 400px; padding:0 1em 1em 1em;">
    <h3>Form add</h3>
    @if(@$resAdd['data'])
        <h5>Add user success!</h5>
    @endif
    <label>
        <p>name</p>
        <input name="name"/>
    </label>
    <br/><br/>
    <button>submit</button>
    <input type="hidden" name="add">
</form>
{{-- ./end add --}}

{{-- ./begin del --}}
@php
    if(request()->has('del')){
    $id = request()->id;
    $resDel = $http->delete('/users/'.$id)->json();
    }
@endphp
@if(@$resDel['status'])
    <h3>Delete success!</h3>
@endif
{{-- ./end del --}}

{{-- ./begin edit --}}
@php
if(request()->edit){
    $id = request()->id;
    $data = request()->all();
    $resEdit = $http->put('/users/'.$id, $data)->json();
}
@endphp
@if(request()->has('edit'))
    @php
        $id = request()->id;
        $user = $http->get('/users/'.$id)->data();
    @endphp
    <form style="border: blue solid 1px; width: 400px; padding:0 1em 1em 1em;">
        <h3>Form edit <a href="{{$config->url}}">[x]</a></h3>
        @if(@$resEdit['status'])
            <h5>Edit success!</h5>
        @endif
        <label>
            <p>name</p>
            <input name="name" value="{{@$user['name']}}"/>
        </label>
        <br/><br/>
        <img width="30" src="{{$media->set(@$user['avatar'])->first()}}"/>
        <br/><br/>
        <button>submit</button>
        <input type="hidden" name="edit" value="submit">
        <input type="hidden" name="id" value="{{@$user['id']}}">
    </form>
@endif
{{-- ./end edit --}}


<hr>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Avatar</th>
        <th>CreatedAt</th>
        <th>Action</th>
    </tr>
    @php
        $users = $http->get('/users')->data();
    @endphp
    @foreach($users as $key => $item)
        <tr>
            <td>{{$item['id']}}</td>
            <td>{{$item['name']}}</td>
            <td><img width="30" src="{{$media->set($item['avatar'])->first()}}"/></td>
            <td>{{\Carbon\Carbon::parse($item['createdAt'])->format('d/m/Y')}}</td>
            <td>
                <a href="?del&id={{$item['id']}}">Del</a> /
                <a href="?edit&id={{$item['id']}}">Edit</a>
            </td>
        </tr>
    @endforeach
</table>
</body>
</html>
