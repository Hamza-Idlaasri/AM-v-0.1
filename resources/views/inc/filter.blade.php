<div class="container p-2">
    <form action="{{ route($route) }}" method="get">

        @if($type == 'Host' || $type == 'Box')

            <label class="font-weight-bold"  for="status">Status :</label>
            <select class="border border-primary mr-3 p-1 rounded text-secondary" name="status" id="status" style="width: 100px">
                <option value="">All</option>
                <option value="up">Up</option>
                <option value="down">Down</option>
                <option value="unknown">Unknown</option>
            </select> 

            <label class="font-weight-bold" for="name">{{ $type }} :</label>
            <select class="border border-primary mr-3 p-1 rounded text-secondary" name="name" id="name" style="width: 150px">
                <option value="">All</option>
                @foreach ($names as $name)
                    <option value="{{ $name->display_name }}">{{ $name->display_name }}</option>
                @endforeach
            </select>

        @else

            <label  class="font-weight-bold" for="status">Status :</label>
            <select class="border border-primary mr-3 p-1 rounded text-secondary" name="status" id="status" style="width: 100px">
                <option value="">All</option>
                <option value="ok">Ok</option>
                <option value="warning">Warning</option>
                <option value="critical">Critical</option>
                <option value="unreachable">Unreachable</option>
            </select>

            <label class="font-weight-bold" for="name">{{ $type }} :</label>
            <select class="border border-primary mr-3 p-1 rounded text-secondary" name="name" id="name" style="width: 150px">
                <option value="">All</option>
                @foreach ($names as $name)
                    <option value="{{ $name->display_name }}">{{ $name->display_name }}</option>
                @endforeach
            </select>

        @endif

        <label class="font-weight-bold" for="from">From :</label>
        <input class="border border-primary mr-3 p-1 rounded text-secondary" type="datetime-local" name="from" min="2015-01-01 00:00:00" max="2021-01-26 00:00:00" id="from">
        

        <label class="font-weight-bold" for="to">To :</label>
        <input class="border border-primary mr-3 p-1 rounded text-secondary" type="datetime-local" name="to" min="2015-01-01 00:00:00" max="2021-01-26 00:00:00" id="to">

        <button type="submit" class="btn btn-success"><i class="fas fa-filter"></i></button>
    </form>
</div>