$(function() {
    $('.select2').select2();

    'use strict'
    $('#showalert').css('display','none');
    $('#onsubmitfile').css('display','none');

    $('.needsvalidation').on('submit', function() {
        $('#onsubmitfile').css('display','block');
    })


    
    var forms = document.querySelectorAll('.needsvalidation')

    
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
          form.addEventListener('submit', function (event) {
              if (!form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
              }

              form.classList.add('was-validated')
          }, false)
    })

    $('select[multiple].active.3col').multiselect({
          columns: 2,
          placeholder: 'Select Options',
          search: true,
          selectAll: false,
          maxWidth : 600,
          searchOptions: {
              'default': 'Search '
          },
            
                
          onOptionClick: function(values,element){
          
              var selectId = values.id

              if(selectId == 'excell_origen'){
                  var num = 0;
              }else{
                  var num = selectId.replace('excell', '');

              }
            
              var selected_items = $('#'+selectId).val()

              if(selected_items.length > 1){

                $('#concat'+num).prop('disabled', false);

              }else{

                $('#concat'+num).prop('disabled', true);
                $('#concat'+num).val('');

              }
            
          }    
    });


})


function deleteMap(id){

    confirmModal("Are you Sure to delete this Mapping", function(confirm) {

        if(confirm) {
          $.post(deleteMapping,{id:id,type:'health',_token : token_src},function(data){

                $('#mapps_'+id).remove();
                $('.mappingsaved').html('');

                $.each(data, function(i, val) {
                    $('.mappingsaved').append('<option value="'+i+'">'+val+'</option>');
                  }); 

             $('#removemapping').modal('toggle');

          })
        }   
    });
    
}

function confirmModal(message, callback) {
    var confirmIndex = true;

    var newMessage = message.replace(/(?:\r\n|\r|\n)/g, "<br>");
    $('#modal_confirm_dialog_body').html("" + newMessage + "");
    $('#modal_confirm_dialog').modal('show');

    $('#confirm_cancle').on("click", function() {
        if(confirmIndex) {
            callback(false);
            $('#modal_confirm_dialog').modal('hide');
            confirmIndex = false;
        }
    });

    $('#confirm_ok').on("click", function() {
        if(confirmIndex) {
            callback(true);
            $('#modal_confirm_dialog').modal('hide');
            confirmIndex = false;
        }
    });
}


function addline(){

    $('#addbutton').prop('disabled', true);


    count++;
    //$.get(addlineroute+'/'+count,'',function(data){

        $('#bodymapping').append('<tr id="line'+count+'">\
        <th><select class="form-select select2" name="mapping[]" placeholder="Select an Option" id="mapping'+count+'"></select></th>\
        <td><select class="3col active form-select" name="excell['+count+'][]" data-live-search="true" multiple="multiple" id="excell'+count+'"></select></td>\
        <td><input type="checkbox" name="reference['+count+']"></td>\
        <td><select class="form-select datacrm inline" name="concat['+count+']" placeholder="Select an Option" disabled="disabled" id="concat'+count+'">\
        <option value="" disabled selected hidden>Please Choose...</option>\
        <option value="0">Space ( )</option>\
        <option value="1">Line ( - )</option>\
        <option value="2">Underline ( _ )</option>\
        <option value="3">No Space</option>\
        </select></td>\
        <td><button type="button" class="btn btn-danger inline" onclick="deleteLine(\''+count+'\')">Remove</button></td></tr>');


        $('#mapping'+count).html($('#mapping_origen').html());
        $('#mapping'+count+' option:selected').prop("selected", false);
       
        $('#mapping'+count).select2();
        $('#mapping'+count).select2("destroy");
        $('#mapping'+count).select2();


        
        $('#excell'+count).html($('#excell_origen').html());  
        $('#excell'+count+' option:selected').prop("selected", false);
      
        $('#excell'+count).multiselect({
            columns: 2,
            placeholder: 'Select',
            search: true,
            maxWidth : 600,
            searchOptions: {
                'default': 'Search '
            },
           
            onOptionClick: function(values,element){  

                var selected_items = $('#excell'+count).val()
                if(selected_items.length > 1){

                  $('#concat'+count).prop('disabled', false);


                }else{

                  $('#concat'+count).prop('disabled', true);
                  $('#concat'+count).val('');

                }
            },
            
            
            selectAll: false
          });


          $('#addbutton').prop('disabled', false);
        
   // });

}


function savename(){

    $('#mapname').val( $('#namemapping').val() );

    $('#namemappingmodal').modal('toggle');
    $('#savemap').val(1);

 
    $('#showalert').css('display','block')

    $('#saveloadbtn').prop('disabled', true);
    $('#loadbtn').prop('disabled', true);

    $.post(savemappingroute , $('form#formmapping').serialize(), function(data) {


    });

}


function removeModal(type){

    $('#removem').prop('disabled', true);
    $("#mappingst").html('');

  $.get(get_mappings+'/'+type,{},function(data){

    $.each(data, function(i, val) {
        $('#mappingst').append('<tr id="mapps_'+i+'"><td  width="75%">'+val+'</td><td scope="col"><button class="btn btn-warning" onclick="deleteMap(\''+i+'\')">Delete</button></td></tr>');
    }); 

    $('#removemapping').modal('show');
    $('#removem').prop('disabled', false);

  })
   

}


function savemapping(save){


    if(save == 1){
        $('#namemapping').val('');
        $('#savemapping').val(0);
        $('#namemappingmodal').modal('show');
    }else{


        $('#saveloadbtn').prop('disabled', true);
        $('#loadbtn').prop('disabled', true);

        $.post(savemappingroute , $('form#formmapping').serialize(), function(data) {

          $('#showalert').css('display','block')
        
        });
    }

}


function deleteLine(idline){
    $('#mapping'+idline).select2("destroy");
    $('#line'+idline).remove();
    
    $('.select2').select2().reset();
    
}


function showimport(){

    var type = $('#type').val();    
    $.get(get_mappings+'/'+type,{},function(data){
        $('.mappingsaved').html('');
        $('.mappingsaved').html('');

        $.each(data, function(i, val) {
            $('.mappingsaved').append('<option value="'+i+'">'+val+'</option>');
        }); 
    })
}
