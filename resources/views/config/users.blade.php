@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/users.css') }}">

<div class="container my-2">
    <a href="{{ route('register') }}" class="btn btn-success"><i class="fas fa-plus"></i> Add User</a>
</div>

<div class="container my-3 back">

    <table class="table table-bordered">
        <thead class="text-center text-primary">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>User Type</th>
                <th>Created At</th>
                <th>Edit</th>
            </tr>    
        </thead>

        <?php $i=0?>

        @forelse ($users as $user)
            @if (auth()->user() != $user)

            <?php $i++ ?>
            
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>

                @if($user->hasRole('agent'))
                    <td>Agent</td>
                @else
                    <td>Superviseur</td>
                @endif
                
                <td>{{ $user->created_at }}</td>
                <td class="p-0">
                    
                    <div class="d-flex my-3 justify-content-around">
                        {{-- Upgrade User --}}
                        @if ($user->hasRole('agent'))
                            <div class="">
                                <div class="check">
                                    <input type="checkbox" name="users[]" form="up" value="{{$user->id}}" checked>
                                </div>
                            </div>
                        @else
                            <div class="">
                                <div class="check">
                                    <input type="checkbox" name="users[]" form="up" value="{{$user->id}}">
                                </div>
                            </div>
                        @endif
                        
                        {{-- Delete User --}}
                        <div class="bg-primary">
                            <button title="delete" class="d-btn text-danger" onclick="show({{$i}})"><i class="fas fa-trash fa-lg"></i></button>
                        </div>
                        
                        <div class="popup{{$i}} container p-3 bg-white shadow rounded pop" style="opacity:1">
                            <h6><b>Are you sure?</b></h6>
                            <p>Do you really you want to delete the user <b>"{{$user->name}}"</b> ?</p>
                            <form action="{{ route('user.delete', $user->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="delete" class="btn btn-danger">Delete</button>
                            </form>
                            <button type="submit" title="Cancel" class="btn btn-light border border-secondary d-inline" onclick="cancel({{$i}})">Cancel</button>
                        </div>
                        
                    </div>
                    
                </td>
            </tr>
            @endif
        @empty
            <tr>
                <td colspan="6">No result found</td>
            </tr>
        @endforelse
        

    </table>
    
<form action="{{ route('user.upgrade') }}" method="post" id="up">
    @csrf
    <button type="submit" class="btn btn-primary">Save</button>
</form>

</div>


<script>
    
show = (i) => {
    document.querySelector('.popup'+i).style.display = 'block';
}

cancel = (i) => {
    document.querySelector('.popup'+i).style.display = 'none';
}

</script>

@endsection