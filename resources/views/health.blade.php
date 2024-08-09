<!DOCTYPE html>
<html lang="es">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Health Sherpa |Lavita</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
   <link rel="stylesheet" href="{{ asset('css/jquery.multiselect.css') }}">
   <link rel="stylesheet" href="{{ asset('css/mapping.css') }}">
</head>

  
<body>
  <div class="modal" id="namemappingmodal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Mapping Name</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="formFile" class="form-label">Mapping name for saving</label>
                    <input class="form-control" name="namemapping" type="text" id="namemapping" required>
                    
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success" onclick="savename()">Save</button>
        </div>
      </div>
    </div>
</div>
<div class="modal" id="removemapping" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Remove Mapping</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="col-md-12">
           
              <table class="table">
                <thead>
                  <tr class="table-dark">
                    <th scope="col" width="75%">Name Mapping</th>
                    <th scope="col">Delete</th>
                  </tr>
                </thead>
                <tbody id="mappingst">
                </tbody>

              </table>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- confirmModal -->
<div class="modal fade" id="modal_confirm_dialog" role="dialog" aria-labelledby="modal_confirm_dialog_label" aria-hidden="true" style="z-index: 8192">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_confirm_dialog_label">
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal_confirm_dialog_body" >
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="confirm_cancle">Cancel</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="confirm_ok">Yes</button>
      </div>
    </div>
  </div>
</div>
<!-- modal alert, confirm dialog } -->
   
<div class="container">
    <div class=" text-center mt-3">
        <img src="{{ asset('images/lavita.png') }}" width="400" class="img-fluid">
        <h1 class="mt-4"></h1>
    </div>

    
    <div class="row">
        <div class="col-lg-12 mx-auto">

          <div class="card mt-2 mx-auto p-4 bg-light" id="blockimport">

                @if( $fileName == null)
                    <form method="POST" enctype="multipart/form-data" class="needsvalidation">
                        @csrf

                        <div class="row">
                          <div class="col-md-6">
                            
                            <label for="exampleInputEmail1" class="form-label">Import Type</label>
                              {{  Form::select('type',['bob' => 'BOB','health'=>'Health Sherpa'],'',array('class'=>'form-select','placeholder'=>'Select type', 'data-live-search'=>'true','id'=>'type','onchange'=>'showimport()','required'=>'required')) }}
                            
                          </div>

                          <div class="col-md-6">
                            <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email </label>
                              {{  Form::email('email','',array('class'=>'form-control','id'=>'email','required'=>'required')) }}
                            </div>
                          </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Upload XLS or XLSX file</label>
                                    <input class="form-control" name="exportto" type="file" id="formFile" accept=
                                    ".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                              <div class="mb-3">
                                @if(count($mapp) >0)
                                <label for="exampleInputEmail1" class="form-label">Saved mappings  
                                  <small class="m-3"><button class="btn btn-warning btn-sm float-end" id="removem" type="button" onclick="removeModal('health')"> Remove Mapping</button></small></label>
                                  {{  Form::select('mapping',$mapp??[],$load_map??'',array('class'=>'form-select mappingsaved','placeholder'=>'Saved mappings', 'data-live-search'=>'true')) }}
                                @endif
                              </div>
                            </div>
                        </div>
                    
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success" id="">Load File</button>
                            </div>
                        </div>

                        <div class="row">
                          <div class="col-md-12">
                            <div class="alert alert-warning" role="alert" id="onsubmitfile">
                              Loading Information ...
                              <div class="spinner-border text-success" role="status">
                                  <span class="visually-hidden">Loading File ...</span>
                              </div>
                            </div>
                          </div>
                        </div>
                    </form>
               @else
              
                  <div class="row">
                    <div class="col-md-6">
                        <label for="formFile" class="form-label">File Uploaded : {{ $fake }}</label>
                    </div>
                    <div class="col-md-3">
                        @if(1==0)
                        <label for="formFile" class="form-label"> : {{ $fake }}</label>
                        @endif
                    </div>
                    <div class="col-md-3">
                      <label for="formFile" class="form-label"><a href="{{ route('load_file') }}">Load Other File </a></label>
                    </div>
                  </div>

              @endif
                <hr/>
                
                
                @if($show == 1)
                <form id="formmapping" method="post" enctype="multipart/form-data">
                    @csrf
                    @php $saved = (($mapping_selected??'' != '')? 1:0) @endphp

                    <input type="hidden" value="{{ $saved }}" name="mappsaved">
                    <input type="hidden" value="{{ $mapping_selected??'0' }}" name="id_map" id="id_map">
                    <input type="hidden" name="savemap" id="savemap" value="0">
                    <input type="hidden" name="mapname" id="mapname" value="">

                    <input type="hidden" name="typeimport" value="{{ $type??'health' }}"> 
                    <input type="hidden" name="filename" value="{{ $fileName??null }}">
                    <input type="hidden" name="email"  value="{{ $email??'' }}" />


                    
                    <div class="row">
                    
                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col" width="35%">Datacrm Fields</th>
                            <th scope="col" width="35%">Excel Headers</th>
                            <th scope="col" width="5%"> Reference</th>
                            <th scope="col" width="15%">Concat Item</th>
                            <th scope="col" width="10%">Remove</th>
                            </tr>
                        </thead>
                        <tbody id="bodymapping">
                        @if(count($load_map) > 0)
                            @php $i=0 @endphp
                            @foreach($load_map as $maps)
                                @php  
                                    $excelid = ( ($i == 0)? "excell_origen":"excell".$i); 
                                    $contactid = 'concat'.$i ;       
                                    $exc = explode(',',$maps['excel']);
                                    $dis = ((count($exc) > 1)? '' : 'disabled') ;
                              @endphp
                                <tr id="line{{ $i }}">
                                    <th>{{  Form::select('mapping[]',['Opportunities' => $potentials,'Clients' => $clients,
                                            ],$maps['module'].'_'.$maps['type'].'_'.$maps['field'],
                                            array('class'=>'form-select select2', 'placeholder'=>'Select CRM Field', 'id'=>'mapping_origen')) }}</th>
                                            
                                    <td>{{  Form::select('excell['.$i.'][]',$excelloptions??[],$exc,array('class'=>'3col active form-select','data-live-search'=>'true','multiple'=>'multiple','aria-label'=>'','id'=> $excelid  ) ) }}</td>
                                    
                                    
                                    <td>{{ Form::checkbox('reference['.$i.']',$i, (($maps['reference'] == 1)? TRUE:FALSE ) ) }}</td>
                                    <td>{{ Form::select('concat['.$i.']',$contacts,'',array('class'=>'form-select datacrm',$dis,'placeholder'=>'Seleccione un mapeo','id'=> $contactid)) }}</td>
                                    <td><button type="button" class="btn btn-danger" onclick="deleteLine('{{ $i }}')">Remove</button></td>
                                </tr>
                                @php $i++ @endphp
                            @endforeach
                        @else   
                        <tr id="line0">
                            <th>{{  Form::select('mapping[]',[
                                        'Opportunities' => $potentials,
                                        'Clients' => $clients,
                                    ],'',array('class'=>'form-select select2', 'placeholder'=>'Select CRM Field', 'id'=>'mapping_origen')) }}</th>
                                    
                            <td>{{  Form::select('excell[0][]',$excelloptions??[],'',array('class'=>'3col active form-select','data-live-search'=>'true','multiple'=>'multiple','aria-label'=>'','id'=>'excell_origen')) }}</td>
                            <td >{{ Form::checkbox('reference[0]','0',array('class'=>'')) }}</td>
                            <td>{{ Form::select('concat[0]',$contacts,'',array('class'=>'form-select datacrm','disabled'=>'disabled','placeholder'=>'Select Option','id'=>'concat0')) }}</td>
                            <td></td>
                        </tr>
                        @endif
                        
                        </tbody>
                    </table>

                    <div class="col-md-12 ">
                      <button type="button" class="btn btn-outline-primary  p-2 mb-3" id="addbutton" onclick="addline()">Add Field</button>
                    </div>
                    <div class="alert alert-success" role="alert" id="showalert">
                      Info uploaded ! Updating information in process
                    </div>
                    <hr/>
                    <div class="col-md-12 float-end">
                        <button type="button" class="btn btn-outline-primary float-end p-2 mb-3 ml-3" id="loadbtn" onclick="savemapping('0')">Load</button> &nbsp;
                        &nbsp;
                        <button type="button" class="btn btn-outline-success float-end p-2 mb-3 ml-3" id="saveloadbtn" onclick="savemapping('1')">Load and Save</button> &nbsp;
                        
                    </div>
                    </div>
                </form>
                @endif
                
            </div>
        </div>
    </div>
  </div>  
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="{{ asset('js/jquery.multiselect.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>
    var deleteMapping     = '{{ route("deleteMapping") }}'
    var addlineroute      = '{{ route("addlines") }}'
    var savemappingroute  = '{{ route("save-mapping") }}'
    var get_mappings      = '{{ route("getMapping") }}'
    var token_src         = '{{csrf_token()}}'
    var count             = {{ (isset($load_map))? count($load_map):0 }}
</script>

<script src="{{ asset('js/mapping.js') }}"></script>
</body>  
</html>