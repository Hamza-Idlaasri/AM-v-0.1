<div class="container p-2">
    <form action="{{ route($route) }}" method="get">

        @if($type == 'Host' || $type == 'Box')

            @if ($from == 'historic')
                <label class="font-weight-bold"  for="status">Status :</label>
                <select class="border border-primary mr-3 p-1 rounded text-secondary" name="status" id="status" style="width: 100px">
                    @if (request('status'))
                        @if(!strcasecmp(request('status'),''))
                            <option value="">All</option>
                            <option value="up">Up</option>
                            <option value="down">Down</option>
                            <option value="unknown">Unknown</option>
                        @endif
                        @if(!strcasecmp(request('status'),'up'))
                            <option value="">All</option>
                            <option value="up" selected>Up</option>
                            <option value="down">Down</option>
                            <option value="unknown">Unknown</option>
                        @endif
                        @if(!strcasecmp(request('status'),'down'))
                            <option value="">All</option>
                            <option value="up">Up</option>
                            <option value="down" selected>Down</option>
                            <option value="unknown">Unknown</option>
                        @endif
                        @if(!strcasecmp(request('status'),'unknown'))
                            <option value="">All</option>
                            <option value="up">Up</option>
                            <option value="down">Down</option>
                            <option value="unknown" selected>Unknown</option>
                        @endif
                    @else
                        <option value="">All</option>
                        <option value="up">Up</option>
                        <option value="down">Down</option>
                        <option value="unknown">Unknown</option>
                    @endif
                    
                </select>
            @endif

            <label class="font-weight-bold" for="name">{{ $type }} :</label>
            <select class="border border-primary mr-3 p-1 rounded text-secondary" name="name" id="name" style="width: 150px">
               
                @if (request('name'))
                    <option value="">All</option>
                    @foreach ($names as $name)
                        @if (!strcasecmp(request('name'), $name->display_name ))
                            <option value="{{ $name->display_name }}" selected>{{ $name->display_name }}</option>
                        @else
                            <option value="{{ $name->display_name }}">{{ $name->display_name }}</option>
                        @endif
                    @endforeach
                @else
                    <option value="">All</option>
                    @foreach ($names as $name)
                        <option value="{{ $name->display_name }}">{{ $name->display_name }}</option>
                    @endforeach
                @endif

            </select>

        @else

            @if ($from == 'historic')
                <label  class="font-weight-bold" for="status">Status :</label>
                <select class="border border-primary mr-3 p-1 rounded text-secondary" name="status" id="status" style="width: 100px">
                    @if (request('status'))
                        @if(!strcasecmp(request('status'),''))
                            <option value="" selected >All</option>
                            <option value="ok">Ok</option>
                            <option value="warning">Warning</option>
                            <option value="critical">Critical</option>
                            <option value="unreachable">Unreachable</option>
                        @endif
                        @if(!strcasecmp(request('status'),'ok'))
                            <option value="" >All</option>
                            <option value="ok" selected>Ok</option>
                            <option value="warning">Warning</option>
                            <option value="critical">Critical</option>
                            <option value="unreachable">Unreachable</option>
                        @endif
                        @if(!strcasecmp(request('status'),'warning'))
                            <option value="">All</option>
                            <option value="ok">Ok</option>
                            <option value="warning" selected>Warning</option>
                            <option value="critical">Critical</option>
                            <option value="unreachable">Unreachable</option>
                        @endif
                        @if(!strcasecmp(request('status'),'critical'))
                            <option value="" >All</option>
                            <option value="ok">Ok</option>
                            <option value="warning">Warning</option>
                            <option value="critical" selected >Critical</option>
                            <option value="unreachable">Unreachable</option>
                        @endif
                        @if(!strcasecmp(request('status'),'unreachable'))
                            <option value="">All</option>
                            <option value="ok">Ok</option>
                            <option value="warning">Warning</option>
                            <option value="critical">Critical</option>
                            <option value="unreachable" selected >Unreachable</option>
                        @endif
                    
                    @else
                        <option value="">All</option>
                        <option value="ok">Ok</option>
                        <option value="warning">Warning</option>
                        <option value="critical">Critical</option>
                        <option value="unreachable">Unreachable</option>
                    @endif
                   
                </select>
            @endif

            <label class="font-weight-bold" for="name">{{ $type }} :</label>
            <select class="border border-primary mr-3 p-1 rounded text-secondary" name="name" id="name" style="width: 150px">
                @if (request('name'))
                <option value="">All</option>
                @foreach ($names as $name)
                    @if (!strcasecmp(request('name'), $name->display_name ))
                        <option value="{{ $name->display_name }}" selected>{{ $name->display_name }}</option>
                    @else
                        <option value="{{ $name->display_name }}">{{ $name->display_name }}</option>
                    @endif
                @endforeach
                @else
                    <option value="">All</option>
                    @foreach ($names as $name)
                        <option value="{{ $name->display_name }}">{{ $name->display_name }}</option>
                    @endforeach
                @endif
            </select>

        @endif

        <label class="font-weight-bold" for="from">From :</label>
        <input class="border border-primary mr-3 p-1 rounded text-secondary" type="date" name="from" min="2015-01-01" max="{{ date('Y-m-d') }}" id="from" value="{{ request('from') }}">
        

        <label class="font-weight-bold" for="to">To :</label>
        <input class="border border-primary mr-3 p-1 rounded text-secondary" type="date" name="to" min="2015-01-01" max="{{ date('Y-m-d') }}" id="to" value="{{ request('to') }}">

        <button type="submit" class="btn btn-success"><i class="fas fa-filter"></i></button>
        
    </form>
</div>