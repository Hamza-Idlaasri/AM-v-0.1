@extends('layouts.app')

@section('content')

<div class="container p-3">

    <form action="{{ route('createEG') }}" method="get">
        <label for="hg_name"><b>Equipgroup Name <span class="text-danger">*</span></b></label>
        <input type="text" name="equipgroup_name" class="form-control w-50 @error('equipgroup_name') border-danger @enderror" id="hg_name" value="{{ old('equipgroup_name') }}" pattern="[a-zA-Z][a-zA-Z0-9-_+ ]{2,20}" title="Equip. name must be between 2 & 20 charcarters in length and containes only letters, numbers, and these symbols -_+">
        @error('equipgroup_name')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
        
        <br>
        
        <label for="mbrs"><b>Members <span class="text-danger">*</span></b></label>
        <br>
        @error('members')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
        @forelse ($equips as $equip)
            <input type="checkbox" name="members[]" id="mbrs" value="{{$equip->service_object_id}}"> {{$equip->equip_name}} <span class="text-secondary">({{$equip->box_name}})</span>
            <br>
        @empty
            <p>No equipments</p>
        @endforelse

        <br>

        <button class="btn btn-primary">Add</button>

    </form>

</div>

@endsection