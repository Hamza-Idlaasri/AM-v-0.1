@extends('layouts.app')

@section('content')

<div class="container p-3">
    <table class="table text-center table-bordered table-streped">
        <tr class="bg-primary text-white">
            <th>Site</th>
            <th>Equipements</th>
            <th>Boxs</th>
        </tr>

        <tr>
            <td><a href="{{ route('site', ['site' => 'Rabat']) }}">Rabat</a></td>
            <td class="text-left">
                <li class="">Armoire Electrique</li>
                <li class="">Climatiseur de Pression 1</li>
                <li class="">Redresseur</li>
                <li class="">Thermostat d'Ambiance 1</li>
                <li class="">Thermostat d'Ambiance 2</li>
            </td>
            <td class="text-left">
                <li>Box-1</li>
                <li>Box-2</li>
            </td>
        </tr>

        <tr>
            <td><a href="{{ route('site', ['site' => 'Casablanca']) }}">Casablanca</a></td>
            <td class="text-left">
                <li class="">Armoire Electrique</li>
                <li class="">Climatiseur de Pression 1</li>
                <li class="">Thermostat Ambiance</li>
            </td>
            <td class="text-left">
                <li>Box-1</li>
                <li>Box-2</li>
            </td>
        </tr>
        
        <tr>
            <td><a href="{{ route('site', ['site' => 'Settat']) }}">Settat</a></td>
            <td class="text-left">
                <li class="">Armoire Electrique</li>
                <li class="">Climatiseur de Pression 1</li>
                <li class="">Climatiseur de Pression 2</li>
                <li class="">Redresseur</li>
                <li class="">Thermostat Ambiance</li>
            </td>
            <td  class="text-left">
                <li>Box-1</li>
            </td>
        </tr>
    </table>
</div>

@endsection