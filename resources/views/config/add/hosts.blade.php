@extends('layouts.app')

@section('content')
    
<div class="container">
    
    <form action="{{ route('addHost',['type'=>$type]) }}" method="get">
        <div class="clearfix">
            <div class="container w-50 my-3 p-3 float-left rounded bg-white shadow-sm">
                
                <h4>Define Host :</h4>
                <label for="host_name"><b>Host Name <span class="text-danger font-weight-bolder">*</span></b></label>
                <input type="text" name="hostName" class="form-control @error('hostName') border-danger @enderror" id="host_name">
                @error('hostName')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror

                <br>

                <label for="ip"><b>IP Address <span class="text-danger font-weight-bolder">*</span></b></label>
                <input type="text" name="addressIP" class="form-control @error('addressIP') border-danger @enderror" id="ip" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$">
                @error('addressIP')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror

                <br>
                
                @if ($type == 'switch' || $type == 'router' || $type == 'printer')
                  {{-- Community String --}}
                  <label for="community"><b>Community String <span class="text-danger font-weight-bolder">*</span></b></label>
                  <input type="text" name="community" class="form-control @error('community') border-danger @enderror" id="community" value="public">
                  @error('community')
                      <div class="text-danger">
                          {{ $message }}
                      </div>
                  @enderror
                  <br>
                  {{-- Number of ports --}}
                  <label for="pNbr"><b>Ports Number <span class="text-danger font-weight-bolder">*</span></b></label>
                  <input  type="number" min="1" max="50" name="portsNbr" class="iNbr1 form-control w-75 @error('portsNbr') border-danger @enderror" id="pNbr" value="1">
                  @error('portsNbr')
                      <div class="text-danger">
                          {{ $message }}
                      </div>
                  @enderror
                @endif
            
            </div>

            <div class="container w-50 my-0 p-3 float-right">
                <div class="container rounded bg-white p-3 shadow-sm">
                    <h4>Parent :</h4>
                    <div class="sizing" style="height:100px;overflow: auto">
                        
                        @foreach ($hosts as $host)
                            <input type="radio" name="hosts" value="{{$host->host_name}}"> {{$host->host_name}}
                            <br>
                        @endforeach
                       
                    </div>
                </div>
            </div>
        </div>
      
        <button type="submit" class="btn btn-primary">Create</button>
    </form>

</div>

<script>

(function($) {
  $.fn.uncheckableRadio = function() {
    var $root = this;
    $root.each(function() {
      var $radio = $(this);
      if ($radio.prop('checked')) {
        $radio.data('checked', true);
      } else {
        $radio.data('checked', false);
      }
        
      $radio.click(function() {
        var $this = $(this);
        if ($this.data('checked')) {
          $this.prop('checked', false);
          $this.data('checked', false);
          $this.trigger('change');
        } else {
          $this.data('checked', true);
          $this.closest('form').find('[name="' + $this.prop('name') + '"]').not($this).data('checked', false);
        }
      });
    });
    return $root;
  };
}(jQuery));

$('[type=radio]').uncheckableRadio();

</script>

@endsection