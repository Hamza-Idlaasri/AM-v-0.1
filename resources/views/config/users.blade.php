@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/users.css') }}">

<div class="container my-3">

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
        
        @forelse ($users as $user)
            @if (auth()->user() != $user)

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
                <td>
                    <div>
                        @if ($user->hasRole('agent'))
                            <div class="float-left">
                                <form action="" method="">
                                    <div class="check">
                                        <input type="checkbox" name="" id="" checked>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="float-left">
                                <form action="{{ route('user.upgrade', $user->id) }}" method="get">
                                    <div class="check">
                                        <input type="checkbox" name="upgrade">
                                    </div>
                                </form>
                            </div>
                        @endif
                        
                        
                        <div class="float-right">
                            <form action="{{ route('user.delete', $user->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="delete" class="btn text-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                        
                    </div>
                    
                </td>
            </tr>
            @endif
        @empty
            <tr>
                <td>No result found</td>
            </tr>
        @endforelse
        

    </table>
    
    {{-- <div class="bg-white shadow rounded w-25 p-2" id="d-popup">
        <p>You are shoore </p>
        <div class="d-inline">
            <form action="{{ route('user.delete', $user->id) }}" method="post">
                @csrf
                @method('DELETE')
                {{$user->id}}
                <button type="submit" title="delete" class="btn btn-danger">Yes</button>
            </form>
        </div>

        <div class="d-inline">
            <button class="btn btn-light">No</button>
        </div>
    </div> --}}
    
</div>

@endsection