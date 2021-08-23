@extends('layouts.app')

@section('content')

<div class="container">
    
    <form action="{{ route('addBox') }}" method="get">
        <div class="clearfix">
            <div class="container w-50 my-2 p-3 float-left rounded bg-white shadow-sm">
                
                <h4>Define Box :</h4>
                <label for="box_name"><b>Box name <span class="text-danger">*</span></b> </label>
                <input type="text" name="boxName" class="form-control @error('boxName') border-danger @enderror" id="box_name" value="{{ old('boxName') }}">
                @error('boxName')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror

                <br>
                <label for="ip"><b>IP Address <span class="text-danger">*</span></b></label>
                <input type="text" name="addressIP" class="form-control @error('addressIP') border-danger @enderror" id="ip" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" value="{{ old('addressIP') }}">
                @error('addressIP')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
        
            </div>

            <div class="container w-50 p-0 float-right">
                <div class="container rounded bg-white m-2 p-3 shadow-sm" style="height:200px">
                    <h4>Parent :</h4>
                    <div class="sizing" style="height:200px;overflow: auto">
                        
                        @foreach ($hosts as $host)
                            <input type="radio" name="hosts" value="{{$host->host_name}}"> {{$host->host_name}}
                            <br>
                        @endforeach
                     
                    </div>
                </div>
            </div>
        </div>
        <div class="container p-3 rounded bg-white shadow-sm">
            <h4>Define Equipements :</h4>
            <div class="container p-3 defineEquip">
                <div class="equip1 d-flex w-100 my-3">
                    <div class="w-50">
                        <label for="equip_name"><b>Equipement name <span class="text-danger">*</span></b></label>
                        <input type="text" name="equipName[]" class="eqName1 form-control w-75 @error('equipName.*') border-danger @enderror" id="equip_name" value="{{ old('equipName.*')}}">
                        @error('equipName.*')
                            <div class="text-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="w-50">
                        <label for="input"><b>Input Number <!--<span class="text-danger">*</span>--></b></label>
                        <input  type="number" min="1" max="10" name="inputNbr[]" class="iNbr1 form-control w-75 @error('inputNbr.*') border-danger @enderror" id="input" value="1">
                        @error('inputNbr.*')
                            <div class="text-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            <span class="btn text-primary bg-white float-right add" title="Add another equipement for monitoring"><i class="fas fa-plus"></i></span>
            <span class="btn text-primary bg-white float-right shadow-sm" id="rmv" title="Remove last equipement" style="display: none"><i class="fas fa-minus"></i></span>            

        </div>
        <br>
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

// Add Equip
const rmv = document.getElementById('rmv');

let i = 2;
const addEquip = document.querySelector('.add');
const Equip = document.querySelector('.equip1');

addEquip.onclick = () => {

    let test = document.createElement('div');
    test.classList.add('equip'+i,'w-100','d-flex','my-3');
    test.innerHTML = Equip.innerHTML;
    document.querySelector('.defineEquip').appendChild(test);

    i++;
    
    if(i > 10)
        addEquip.style.display = 'none';
        
    if (i > 2) {
      rmv.style.display = 'block';
    }
}

// Remove equip

rmv.onclick = () => {

    if (i > 2) {
    document.querySelector('.defineEquip').lastElementChild.remove();
    i--;
    }

    if (i >= 2) {
    addEquip.style.display = 'block';
    }

    if (i == 2) {
        rmv.style.display = 'none';
    }

}

</script>

@endsection