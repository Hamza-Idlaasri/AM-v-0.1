
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/common.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" />
</head>
<body>
    
<div class="container">

    {{-- <table class="table table-striped table-bordered table-hover">

        <tr  class="bg-primary text-light text-center">

            <th>Box</th>
            <th>Equipement</th>
            <th>Status</th>
            <th>Dernier verification</th>
            <th>Description</th>
        </tr>

    
    
        @forelse ($equipements_history as $equipement_history)

        <tr>
   
            <td>{{$equipement_history->host_name}}</td> 

            <td>{{$equipement_history->service_name}}</td>
            
            @switch($equipement_history->state)

                @case(0)
                    <td><span class="badge badge-success">Ok</span></td>
                    @break
                @case(1)
                    <td><span class="badge badge-warning">Warning</span></td>
                    @break
                @case(2)
                    <td><span class="badge badge-danger">Critical</span></td>
                    @break
                @case(3)
                    <td><span class="badge badge-unknown">Ureachable</span></td>
                    @break
                @default
                    
            @endswitch
            
            <td>{{$equipement_history->state_time}}</td>
            <td class="description">{{$equipement_history->output}}</td>
        </tr>
 
        @empty

            <tr>
                <td colspan="5">No result found </td>
            </tr>

        @endforelse
        
    </table> --}}

    <h1>Downloaded</h1>
    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Rerum consequuntur a necessitatibus explicabo, dolor facilis nam incidunt quidem reprehenderit, iusto pariatur tempora? Rem itaque nesciunt ipsa, quos modi provident iusto!</p>
</div>

    

</body>
</html>
    

