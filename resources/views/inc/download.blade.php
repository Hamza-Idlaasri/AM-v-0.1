<style>

    .popup{
        top: 35%;
        left: 35%;
        display: none;
        z-index: 1;
        width: 300px;
        box-shadow: 0px 2px 15px grey;
    }
    
    .close{
        top: 10px;
        right: 10px;   
    }
    
</style>
    
<div class="p-2 ml-0">
    <button id="download" class="btn text-primary p-1"><i class="far fa-file-download fa-2x"></i></button>
</div>

<div class="container bg-white p-3 rounded position-absolute popup">

    <button class="position-absolute btn close">&times;</button>
    
    <br>
    <h5 class="text-center">Choose type of the file</h5>
    <br>

    <div class="d-flex justify-content-around">
        {{-- Download PDF Button --}}
        <a href="{{ route($route) }}" target="_blank" class="btn btn-danger fa-lg"><i class="fas fa-file-pdf"></i> PDF</a>

        {{-- Download Excel Button --}}
        <form action="" method="get" >
            <button type="submit" class="btn btn-success fa-lg"><i class="fas fa-file-excel"></i> Excel</button>
        </form>
    </div>

</div>

<script>

const download = document.querySelector('#download');
const popup = document.querySelector('.popup');
const close = document.querySelector('.close');


close.onclick = () => {
    popup.style.display = 'none';
    back.style.opacity = '1';
}

download.onclick = () => {
    
    popup.style.display = 'block';
    back.style.opacity = '.2';
}
    
</script>
    
    {{-- 
    <div class="container bg-dark popup">
        <div class="row justify-content-center">
            <div class="col-md-7 bg-white shadow rounded p-3">
                <div class="float-right mx-0">
                    <button id="close" class="close btn" data-dismiss="alert" arial-label="close">&times;</button>
                </div>
                <br>
                <form action="">
    
                    <div class="text-center">
                        <h5 class="float-left font-weight-bold">Hosts :</h5>
                        <select name="hosts" id="">
                            <option value="">All</option>
                            <option value="">bf-1010</option>
                        </select>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h5 class="float-left font-weight-bold">Status :</h5>
                        <input type="checkbox" name="ok" id="" checked><span class="badge badge-success ml-2 mr-5"> Ok</span>
                        <input type="checkbox" name="warning" id="" checked><span class="badge badge-warning ml-2 mr-5"> Warning</span>
                        <input type="checkbox" name="critical" id="" checked><span class="badge badge-danger ml-2 mr-5"> Critical</span>
                        <input type="checkbox" name="uknown" id="" checked><span class="badge badge-unknown ml-2"> Unknown</span>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h5 class="float-left font-weight-bold">Period Time :</h5>
                        <label for="from">From :</label>
                        <input type="date" name="from" min="1998-01-01" max="2021-01-01" id="">
                        
                        <label for="to">To :</label>
                        <input type="date" name="to" min="1998-01-01" max="2021-01-01" id="">
                        
                    </div>
                    <hr>
                    <div class="text-center">
                        <h5 class="float-left font-weight-bold">File Type :</h5>
                        <input type="radio" name="file" value="csv" id=""><i class="fas fa-file-excel fa-lg ml-2 "></i><span class="font-weight-bold mr-5"> Excel</span>  
                        <input type="radio" name="file" value="pdf" id="" checked><i class="fas fa-file-pdf fa-lg ml-2 "></i><span class="font-weight-bold mr-5"> PDF</span>
                    </div>
                    <hr>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success font-weight-bold w-25"><i class="fas fa-download"></i>  Download</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div> --}}
    
    