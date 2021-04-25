@extends('layouts.app')

@section('content')

<div class="container p-3">
    <h5 class="text-center">Overview on Site : <b>{{$site}}</b></h5>

    <div class="container d-flex">
        <table class="table table-bordered text-center m-2">
            <tr style="font-size: 10px">
                <th></th>
                <th>Up</th>
                <th>Down</th>
                <th>Unreachable</th>
                <th>All</th>
            </tr>

            <tr>
                <td class="font-weight-bolder"><a href="/configuration/sites/{{$site}}/hosts">Hosts</a></td>
                <td><span class="badge badge-success">10</span></td>
                <td><span class="badge badge-danger">5</span></td>
                <td><span class="badge badge-unknown">2</span></td>
                <td><span class="badge badge-secondary">17</span></td>
            </tr>
            <tr>
                <td  class="font-weight-bolder"><a href="/configuration/sites/{{$site}}/boxs">Boxs</a></td>
                <td><span class="badge badge-success">2</span></td>
                <td><span class="badge badge-danger">1</span></td>
                <td><span class="badge badge-unknown">0</span></td>
                <td><span class="badge badge-secondary">3</span></td>
            </tr>
        </table>

        <table class="table table-bordered text-center m-2">
            <tr style="font-size: 10px">
                <th></th>
                <th>Ok</th>
                <th>Warning</th>
                <th>Critical</th>
                <th>Unknown</th>
                <th>All</th>
            </tr>

            <tr>
                <td class="font-weight-bolder"><a href="/configuration/sites/{{$site}}/services">Services</a></td>
                <td><span class="badge badge-success">10</span></td>
                <td><span class="badge badge-warning">5</span></td>
                <td><span class="badge badge-danger">5</span></td>
                <td><span class="badge badge-unknown">2</span></td> 
                <td><span class="badge badge-secondary">22</span></td>
            </tr>
            <tr>
                <td  class="font-weight-bolder"><a href="/configuration/sites/{{$site}}/equip">Equipements</a></td>
                <td><span class="badge badge-success">15</span></td>
                <td><span class="badge badge-warning">3</span></td>
                <td><span class="badge badge-danger">7</span></td>
                <td><span class="badge badge-unknown">1</span></td>
                <td><span class="badge badge-secondary">26</span></td>
            </tr>
        </table>
    </div>
</div>

@endsection