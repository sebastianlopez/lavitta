<!DOCTYPE html>
<html lang="es">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Encuesta de Calidad |Lavita</title>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    
    <style>

        body {
            font-family: 'Lato', sans-serif;
        }

        h1 {
            margin-bottom: 40px;
        }

        label {
            color: #333;
        }

        .btn-send {
            font-weight: 300;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            width: 80%;
            margin-left: 3px;
            }
        .help-block.with-errors {
            color: #ff5050;
            margin-top: 5px;

        }

        .card{
            margin-left: 1px;
            margin-right: 1px;
        }


        .btn-default:hover {
            color: #FFF;
            background: rgba(108, 88, 179, 0.75);
            border: 2px solid rgba(108, 89, 179, 0.75);
            }

            .btn-primary {
         
            font-size: 16px;
            color: rgba(58, 133, 191, 0.75);
            letter-spacing: 1px;
            line-height: 15px;
            border: 2px solid rgba(58, 133, 191, 0.75);
            border-radius: 50px;
            padding: 14px;
            background: transparent;
            transition: all 0.3s ease 0s;
            }

            .btn-primary:hover {
            color: #FFF;
            background: rgba(58, 133, 191, 0.75);
            border: 2px solid rgba(58, 133, 191, 0.75);
            }


            .donate-now {
            list-style-type: none;
            margin: 5px 0 0 0;
            padding: 0;
            }

            .donate-now li {
            float: left;
            margin: 0 2px 0 0;
            height: 40px;
            position: relative;
            }

            .extra{
                display: inline-block;
                padding-top: 30px;
            }

            .donate-now label,
            .donate-now input {
            display: block;
            
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            }

            .donate-now input[type="radio"] {
                opacity: 0.01;
                z-index: 100;
            }

            .donate-now input[type="radio"]:checked+label,
            .Checked+label {
                background: rgba(58, 133, 191, 0.75);
                border: 2px solid rgba(58, 133, 191, 0.75);
            }

            .donate-now label {

                font-size: 15px;
                color: rgba(58, 133, 191, 0.75);
                letter-spacing: 1px;
                line-height: 15px;
                border: 2px solid rgba(58, 133, 191, 0.75);
                border-radius: 50px;
                padding: 15px;
                background: transparent;
                transition: all 0.3s ease 0s;
            }

            .donate-now label:hover {
                 background: #DDD;
            }

            .form-horizontal{
                display:block;
                width:50%;
                margin:0 auto;
                width:
            }
            .btn-scale {
            min-width: 44px;
            width: 6%;
            text-align: center;
            font-weight: bold;
            color: black;
            font-family: 'Lato', sans-serif;
            }

            .btn-scale-asc-1, .btn-scale-desc-10 {
              background-color: #49c929e0;
            }

            .btn-scale-asc-1:hover,
            .btn-scale-desc-10:hover {
              background-color: #2CDE00;
            }

            .btn-success{
              background-color: #05C3D1;
            }

            .btn-success:hover,
            .btn-success:hover {
              background-color: #05C3D1;
            }

            .btn-scale-asc-2,
            .btn-scale-desc-9{
              background-color: #66FF00;
            }

            .btn-scale-asc-2:hover,
            .btn-scale-desc-9:hover{
              background-color: #59DE00;
            }

            .btn-scale-asc-3,
            .btn-scale-desc-8 {
              background-color: #99FF00;
            }

            .btn-scale-asc-3:hover,
            .btn-scale-desc-8:hover {
              background-color: #85DE00;
            }

            .btn-scale-asc-4,
            .btn-scale-desc-7 {
              background-color: #CCFF00;
            }

            .btn-scale-asc-4:hover,
            .btn-scale-desc-7:hover {
              background-color: #B1DE00;
            }

            .btn-scale-asc-5,
            .btn-scale-desc-6 {
              background-color: #FFFF00;
            }

            .btn-scale-asc-5:hover,
            .btn-scale-desc-6:hover {
              background-color: #DEDE00;
            }

            .btn-scale-asc-6,
            .btn-scale-desc-5 {
              background-color: #FFCC00;
            }

            .btn-scale-asc-6:hover,
            .btn-scale-desc-5:hover {
              background-color: #DEB100;
            }

            .btn-scale-asc-7,
            .btn-scale-desc-4 {
              background-color: #FF9900;
            }

            .btn-scale-asc-7:hover,
            .btn-scale-desc-4:hover {
              background-color: #DE8500;
            }

            .btn-scale-asc-8,
            .btn-scale-desc-3 {
              background-color: #FF6600;
            }

            .btn-scale-asc-8:hover,
            .btn-scale-desc-3:hover {
              background-color: #DE5900;
            }

            .btn-scale-asc-9,
            .btn-scale-desc-2 {
              background-color: #FF3300;
            }

            .btn-scale-asc-9:hover,
            .btn-scale-desc-2:hover {
              background-color: #DE2C00;
            }

            .btn-scale-asc-10,
            .btn-scale-desc-1 {
              background-color: #FF0000;
            }

            .btn-scale-asc-10:hover,
            .btn-scale-desc-1:hover {
              background-color: #DE0000;
            }
            .overbutton{
              color: rgba(255, 255, 255, 1);
              box-shadow: 0 5px 15px rgb(40, 62, 227);
            }
            .page-header {
              padding-bottom: 9px;
              margin: 40px 0 20px;
              border-bottom: 1px solid #eee;
              font-size: 12px;
            }
            .error{
              border: red
            }
    </style>
</head>

<body>
    <div class="container">
        <div class=" text-center mt-3">
            <img src="{{ asset('images/lavita.png') }}" width="400" class="img-fluid"><h1 class="mt-4">Encuesta de Satisfación</h1>
        </div>

    
        <div class="row">
            <div class="col-lg-7 mx-auto">
                <div class="card mt-2 mx-auto p-1 bg-light">
                    <div class="card-body bg-light">
                     @if($show == true) 
                      <div class="container" id="formcontainer">
                          <form id="contact-form" role="form" class="savecase">
                              <div class="controls">

                                  <div class="row ">
                                   
                                      <div class="col-md-12">
                                          <h5  class="text-justify"><strong>Basado en la atención recibida, ¿Cuál es la probabilidad de que nos recomiendes?</strong></h5>
                                          <p class="page-header">1 es poco probable, 10 es muy probable </p>
                                          
                                              <div class="chart-scale text-center">
                                              @for($i=1; $i<11;$i++)  
                                              <button type="button" value="{{ $i }}" class="btn btn-scale btn-scale-desc-{{ $i }} mt-1 mb-1">{{ $i }}</button>
                                              @endfor
                                              <input name="score" id="score" type="hidden" value="" required>
                                              <div class="alert alert-danger scoreerror" role="alert">
                                               Seleccione una calificación
                                              </div>
                                      </div>
                                  </div>
                              
                                  <div class="row mt-5">
                                      <div class="col-md-12">
                                          <div class="form-group">
                                              <h5 class="text-justify"><strong>Ayudanos a mejorar, ¿Cuál es el motivo de la calificación? </strong></h5>
                                                  <textarea id="form_message" name="comments" class="form-control" placeholder="Comentario." rows="4" required data-error="Por favor, deje su comentario."></textarea>
                                                  <input name="case" id="case" type="hidden" value="{{ $case }}">
                                          </div>
                                      </div>
                                  </div>
                                  

                                  <div class="row mt-3">
                                    <div class="col-md-12 text-center">
                                        <input type="button" class="btn btn-success btn-lg" value="Enviar Calificación" >
                                    </div>
                                  </div>
                              </div>
                          </form>
                      </div>
                    @endif
                    <div class="container text-center" id="thanks"><h2>Gracias por llenar nuestra encuesta</h2></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
    var saveurl = "{{ route('saveform') }}";
    var showit  = '{{ $show }}';
    $(document).ready(function () {

        var SaveButton = $('.btn-success');

        $('.savecase').validate();
        $('.alert-danger').hide();

        if(showit != false)
            $('#thanks').hide();
        
        SaveButton.click(function (e) {
           $('.alert-danger').hide();
           $('#form_message').removeAttr('required');

           $(".btn-success").attr("disabled",true);
           var score    = $('#score').val();
           var comments = $('#form_message').val();
           $('#form_message').attr('required', 'required');

           if(score == ''){

              $(".btn-success").attr("disabled",false);
              $('.scoreerror').show()
            
           }else {
            
         
              var score = $('#score').val();
              tosave = $('.savecase').serialize();
              $.post(saveurl,tosave,function(data){

                $('#formcontainer').hide();
                $('#thanks').show();

                if(score > 7){
                  window.location.replace("https://g.page/r/Cc8x9nkyaM3mEAI/review");
                }
                

              });
            }


        });
    });

    $(document).on("click", ".btn-scale", function () {

        $('.btn-scale').removeClass('overbutton')
        $('#score').val($(this).attr('value'));
        $(this).addClass('overbutton')

    })

</script>
</body>  
</html>