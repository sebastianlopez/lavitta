<?php

namespace App\Console\Commands;

use App\Http\Controllers\MailController;
use App\Imports\HealthImport;
use Carbon\Carbon;
use App\Models\Datacrm;
use App\Models\Mapping;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class Importer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importer:excell';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all excell loaded and Mapped';

    /**
     * Create a new command instance.
     *
     * @return void
     */

     private $datacrm;
     private $mailler;
     private $mapping_route;
     private $file_route;
     private $contacts;


     function __construct(Datacrm $crm, MailController $mail){

        parent::__construct();
 
         $this->datacrm         = $crm;
         $this->mapping_route   = 'app/public/mapping/';
         $this->file_route      = 'app/public/uploads/';
         $this->mailler         = $mail;
 
         $this->contacts = array(
 
             0 => 'Space ( )',
             1 => 'Line ( - )',
             2 => 'Underline ( _ )',
             3 => "No Space "
         );
 
     }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pending = Mapping::where('status','pending')->orderBy('id','asc')->first();


        try{

            if($pending != null){
                $pending->update(['status'=>'inprogress']);

          
                $type   = $pending->type;
                $email  = $pending->email;
    
                $collection = Excel::toCollection(new HealthImport, storage_path($this->file_route.$pending->filename));
                $headers    = $collection[0]->toArray();
    
                $file = file_get_contents( storage_path($this->mapping_route.$pending->mapping),true);
                $map = json_decode($file, true);
    
                $simbols = array('0'=>' ','1'=>'-', '2'=>'_','3'=>'');
                $errors = array();   
                foreach($headers as $k => $out){

                    $potentsearch   = array();
                    $contactsearch  = array();
            
                    if($k >0){
                        $con = array();
                        $pot = array();
                        foreach($map as $key => $m){
                            
                            
                        
                            if($m['excel'] > 0 ){
                          
                                $explode    = explode(',',$m['excel']);
                                $value      = '';

                                                
                                if(count($explode) > 1){

                                    
                                    foreach($explode as $ex){
                                     
                                        if($m['concat'] == null)
                                            $forsimbol = 0;
                                        else
                                            $forsimbol = $m['concat'];

                                        $value .= $out[$ex].$simbols[$forsimbol]; 
                                    }


                                    if($m['concat'] != 3){
                                        $value = substr($value, 0, strlen($value)-1);
                                    }

                                }else  {

                                    $value = $out[$m['excel']];

                                }

                                

                                if($m['type'] == 'date'){

                                    $before =$value;
                                   
                                    $value = $this->cleanDate($value);
                                 
                                 
                                }

                                if($m['type'] == 'phone'){

                                    $value = $this->cleanphone($value);

                                }

                                $tosearch =  trim($value);
                                if($m['type'] == 'phone'){

                                    $phone = $value;
                                    $tosearch =  trim($phone);

                                }

                                if($m['module'] == 'pot'){


                                    if($m['field'] == 'cf_1098'){
                                        $pot[$m['field']] =  round($value,5); 


                                    }elseif($m['field'] == 'cf_1712'){
                                         $newvalue = $this->remove_leading_00($value); 

                                        $pot[$m['field']] = $newvalue;
                                        $tosearch         = $newvalue;

                                    
                                    }else{
                                         $pot[$m['field']]               = trim($value);
                                    }


                                    
                                    if($m['reference'] == 1){
                                        $potentsearch[$m['field']] =  trim($tosearch);
                                    }

                                }else{

                                    $con[$m['field']]       = trim($value);

                                    if($m['reference'] == 1){
                                        $contactsearch[$m['field']] =  trim($tosearch);
                                    }

                                }
                            }

                        }

            
                         
                        if( !empty($contactsearch) && !empty($potentsearch) ){


                            $rules = $this->datacrm->rules($contactsearch,$potentsearch,$type);
                            
                            Log::channel('daily')->error('Rule // '.$rules['status'].' // '.$k.'=='.$key);

                            
                            if($rules['status'] == 1){


                                if(array_key_exists('assigned_user_id',$pot)){
                                    unset($pot['assigned_user_id']);
                                }
                                

                                if(array_key_exists('assigned_user_id',$con)){
                                    unset($con['assigned_user_id']);
                                }

                                $con['id'] = $rules['contact']['id'];


                                if($con['cf_1828'] == 1){
                                    $con['cf_1828'] = 'true';
                                }

                                if($con['cf_1828'] == 0){
                                    $con['cf_1828'] = 'false';
                                }

                                $con['assigned_user_id'] = $rules['contact']['assigned_user_id'];

                              
                                $contactresult = $this->datacrm->updateContact($con);

                                if(array_key_exists('error',$contactresult)){
                                    array_push($errors,['row'=>$k+1,'type'=>'Error actualizando Contacto','contactsearch'=>$contactsearch,'potentsearch'=>$potentsearch]);
                                }


                               // if(array_key_exists('subsidy',$pot)){
                                 //   $rules['potential']
                               // }

                                if($pot['cf_1415'] == 'completed'){
                                    $pot['cf_1415'] = 'Completed';
                                }

                                $pot['id']                  = $rules['potential']['id'];
                                $pot['assigned_user_id']    = $rules['potential']['assigned_user_id'];
                                $pot['contact_id']          = $rules['potential']['contact_id'];
                                $pot['potentialname_pick']  = 'Health';
                                $pot['cf_1830']             = $rules['potential']['cf_1706'];
                                $pot['sales_stage']         = $rules['potential']['sales_stage'];


                                
                                if(!array_key_exists('closingdate',$pot)){
                                
                                    $pot['closingdate'] = date('Y-m-d');
                                }else{
                                    if($pot['closingdate'] == '')
                                        $pot['closingdate'] = date('Y-m-d');
                                    
                                }

                               
                                if($pot['cf_1082']  != 'Finished' && $rules['potential']['sales_stage'] != 'Closed Lost')
                                    $potentialresult = $this->datacrm->updatePotential($pot);

                                if(array_key_exists('error',$potentialresult)){
                                    array_push($errors,['row'=>$k+1,'type'=>'Error actualizando oportunidad','contactsearch'=>$contactsearch,'potentsearch'=>$potentsearch]);
                                }

                            }



                            if($rules['status'] == 2){

                                $usersearch = '';

                                if(array_key_exists('assigned_user_id',$con)){
                                    $usersearch = $con['assigned_user_id'];
                                    unset($con['assigned_user_id']);
                                }

                                if(array_key_exists('assigned_user_id',$pot) && $usersearch==''){      
                                    $usersearch = $pot['assigned_user_id'];
                                    unset($pot['assigned_user_id']);
                                }



                                $responasble = $this->datacrm->searchUser(trim($usersearch));

                                $responasble['id'] = ($responasble == null)? '19x24':$responasble['id'];
                                

                                $con['id']     = $rules['contact']['id'];
                                $contactresult = $this->datacrm->updateContact($con);

                                if(array_key_exists('error',$contactresult)){
                                    array_push($errors,['row'=>$k+1,'type' => 'Error Actualizando Contacto','contactsearch'=>$contactsearch,'potentsearch'=>$potentsearch]);
                                }


                                $pot['contact_id']          = $con['id'];
                                $pot['potentialname_pick']  = 'Health';
                                $pot['assigned_user_id']    = $responasble['id'];
                                $pot['sales_stage']         = 'Closed Won';
                                $pot['potentialname']       = 'Health';
                                $pot['cf_1830']             = 0;

                                if($pot['cf_1415'] == 'completed'){
                                    $pot['cf_1415'] = 'Completed';
                                }



                                if(!array_key_exists('closingdate',$pot)){
                                
                                    $pot['closingdate'] = date('Y-m-d');
                                }else{
                                    if($pot['closingdate'] == '')
                                        $pot['closingdate'] = date('Y-m-d');
                                    
                                }


                                
                                if (date("d", strtotime($pot['cf_1094'])) == "01") {
                                    $resultPotential =$this->datacrm->createInfo($pot,'Potentials');


                                    if(array_key_exists('error',$resultPotential)){
                                        array_push($errors,['row'=>$k+1,'type' => 'Error creando Oportunidad','contactsearch'=>$contactsearch,'potentsearch'=>$potentsearch]);
                                    }
    

                                }else{
                                    array_push($errors,['row'=>$k+1,'type' => 'Fecha no comienza primer dia del mes','contactsearch'=>$contactsearch,'potentsearch'=>$potentsearch]);
                                }
                                
         

                                //if($pot['cf_1082']  != 'Finished')
                                    
                                
                            }


                            if($rules['status'] == 3){

                               $usersearch = '';
                                
                                
                                if(array_key_exists('assigned_user_id',$con)){
                                    $usersearch = $con['assigned_user_id'];
                                    unset($con['assigned_user_id']);
                                }

                                if(array_key_exists('assigned_user_id',$pot) && $usersearch==''){      
                                    $usersearch = $pot['assigned_user_id'];
                                    unset($pot['assigned_user_id']);
                                }

                               
                                $responasble = $this->datacrm->searchUser(trim($usersearch));

                                $responasble['id'] = ($responasble == null)? '19x24':$responasble['id'];

                            
                                $con['assigned_user_id']    = $responasble['id'];
                                $con['cf_1423']             = 'LaVita';
                                $con['mailingcountry']      = 'United States';
                                
                
                                $contactresult = $this->datacrm->createInfo($con,'Contacts');


                                if(array_key_exists('error',$contactresult)){

                                    array_push($errors,['row'=>$k+1,'type'=>'Error creando Contacto','contactsearch'=>$contactsearch,'potentsearch'=>$potentsearch]);

                                }else{


                                    $pot['contact_id']          = $contactresult['id'];
                                    $pot['potentialname_pick']  = 'Health';
                                    $pot['assigned_user_id']    = $responasble['id'];
                                    $pot['potentialname']       = 'Health';
                                    $pot['sales_stage']         = 'Closed Won';
    
                                     if(!array_key_exists('closingdate',$pot)){
                                    
                                        $pot['closingdate'] = date('Y-m-d');
                                    }else{
                                        if($pot['closingdate'] == '')
                                            $pot['closingdate'] = date('Y-m-d');
                                        
                                    }
                                    
             
                                    if (date("d", strtotime($pot['cf_1094'])) == "01") {

                                        
                                        $resultPotential =$this->datacrm->createInfo($pot,'Potentials');
    
    
                                        if(array_key_exists('error',$resultPotential)){
                                            array_push($errors,['row'=>$k+1,'type' => 'Error creando Oportunidad','contactsearch'=>$contactsearch,'potentsearch'=>$potentsearch]);
                                        }
        
    
                                    }else{
                                        array_push($errors,['row'=>$k+1,'type' => 'Fecha no comienza primer dia del mes','contactsearch'=>$contactsearch,'potentsearch'=>$potentsearch]);
                                    }
                                }


                            }

                            if($rules['status'] > 3 || !isset($rules['status'])){
                               
                                if(!isset($rules['status']))
                                    array_push($errors,['row'=>$k+1,'type'=> 'Error Unknow matching','contactsearch'=>$contactsearch,'potentsearch'=>$potentsearch]);
                                
                                else
                                    array_push($errors,['row'=>$k+1,'type'=>$rules['type'],'contactsearch'=>$contactsearch,'potentsearch'=>$potentsearch]);

                            }
                            

                        }
                                    
                    }
                }


                $pending->update(['status'=>'finished']);
                $this->mailler->sendFinished($pending); 

                if(!empty($errors)){
                    Log::channel('daily')->error(json_encode($errors));
                    $this->mailler->sendErrors($errors,$email); 
                }
            }
        }catch(\Exception $e)   {

             Log::channel('daily')->error($e->getMessage().' '.$k.' -  '.$key);
        }




    }



    function remove_leading_00($str) {

        if (substr($str, 0, 3) === '000') {
            return substr($str, 3);

        }elseif (substr($str, 0, 2) === '00') {
            return substr($str, 2);

        } else {
            return $str;
        }
    }



      /**
     * Removes any strange Caracter from phone number
     *
     * @param [type] $phone
     * @return void
     */
    function cleanphone($phoneNumber){
     
            $cleanedNumber = preg_replace('/\D/', '', $phoneNumber);       
            return $cleanedNumber;
    }


    /**
     * Checks diferents way that date may come and sends it in YYYY-mm-dd format
     *
     * @param [string] $dateString
     * @return void
     */
    function cleanDate($dateString){

        $value = '';

        try{


            if (strpos($dateString, '/') !== false) {
                
                $toformat = explode('/',trim($dateString));

                $month = $toformat[0];
                if(strlen($toformat[0]) == 1){
                    $month = '0'.$toformat[0];
                }

                $day = $toformat[1];
                if(strlen($toformat[1]) == 1){
                    $day = '0'.$toformat[1];
                }

                $value = $toformat[2].'-'.$month.'-'.$day;

            } elseif (strpos($dateString, '-') !== false) {

                $value = $dateString;

            }else if( $value == '' && $dateString != '' ){

                 $value = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateString))->format('Y-m-d') ;
            }
            

        }catch(\Exception $e){
            
             $value = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateString))->format('Y-m-d') ;
          
        }

        return $value;
    }

}
