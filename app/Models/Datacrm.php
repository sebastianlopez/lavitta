<?php

namespace App\Models;

use Salaros\Vtiger\VTWSCLib\WSClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Datacrm extends Model
{
    
    public $username;
    public $token;


    public function __construct()
    {

        $this->username = 'admin';
        $this->token    = '3MU6ed0NGYvk0Cuk';


        try{

            $this->vt = new WSClient('https://develop1.datacrm.la/datacrm/cp2lavita/',
                $this->username, $this->token);

        }catch(\Exception $e){
              
    
          $msg = ['method' => 'Conecting to CRM', 'error' => $e->getMessage()];
          Log::channel('daily')->error(json_encode($msg));

          $this->vt = null;
              
        }

       
    }

    
    /**
     * Undocumented function
     *
     * @param [type] $module
     * @return void
     */
    public function getFields($module){

        return $this->vt->modules->getOne($module);
    }

    /**
     * Return Contact Info by ID
     *
     * @param [type] $id
     * @return void
     */ 
    public function getContactByID($id)
    {
        $client = $this->vt->entities->findOneByID('Contacts', $id, ['id', 'account_id']);

        return $client;
    }





    /**
     * Return Contact Info by Mobile
     *
     * @param [type] $phone
     * @return void
     */
    public function getContactByPhone($phone){

        $product = $this->vt->entities->findOne('Contacts',['mobile' => $phone]);
        return $product;

    }


    /**
     * Return Contact Info by Email
     *
     * @param [type] $email
     * @return void
     */
    public function getContactByEmail($email){

        $product = $this->vt->entities->findOne('Contacts',['email' => $email]);
        return $product;

    }



    /**
     * Returns Contacts by search in select add the fields requiere too return
     *
     * @param [type] $search
     * @param integer $limit
     * @param integer $offset
     * @return void
     */
    public function getContacts($search, $limit = 100, $offset = 0)
    {
        $select = [
            'lastname',             //Nombre Contacto
            'email',
            'mobile',
            'id',
            'assigned_user_id',
            'email',
            'leadsource',           //Origen del prospecto
        ];

        $clients = $this->vt->entities->findMany('Contacts', $search, $select, $limit, $offset);
        return $clients;
    }



    /**
     * Updates Contact information.
     *
     * @param [type] $data
     * @return void
     */
    public function updateContact($data)
    {
        $contact = null;
        
        try {
         
            $contact = $this->vt->entities->updateOne('Contacts', $data['id'], $data);
        
        } catch (\Exception $e) {

            $msg = ['method' => 'updateContact', 'data' => ['id' => $data['id']], 'error' => $e->getMessage()];
            Log::channel('daily')->error(json_encode($msg));

            $contact = array('error' => 'Error updating contact '.$e->getMessage());

        }

        return $contact;
    }


    /**
     * 
     * Modulo Casos o HelpDesk
     * 
     */

    public function getCase($case){

        $product = $this->vt->entities->findOne('HelpDesk',['ticket_no' => $case]);
        return $product;

    }


    /**
     * Updates info in cases.
     *
     * @param [type] $data
     * @return void
     */
    public function updateCase($data){

        $helpDesk = null;
        
        try {
            
            $helpDesk = $this->vt->entities->updateOne('HelpDesk', $data['id'], $data);

        } catch (\Exception $e) {

            $msg = ['method' => 'updateCase', 'data' => ['id' => $data['id']], 'error' => $e->getMessage()];
            Log::channel('daily')->error(json_encode($msg));

        }

        return $helpDesk;

    }


    /**
     * Undocumented function
     *
     * @param array $search
     * @return void
     */
    public function searchOnePotential($search){


        try{
            
            $potential = $this->vt->entities->findOne('Potentials',$search);
            return $potential;

        }catch(\Exception $e){
            
        }

    }


    /**
     * Undocumented function
     *
     * @param [type] $info
     * @return void
     */
    public function updatePotential($info){

        $potential = array();

        try {
            $potential = $this->vt->entities->updateOne('Potentials', $info['id'], $info);
        } catch (\Exception $e) {
            $msg = ['method' => 'updatePotential', 'data' => ['id' => $info['id'],$info], 'error' => $e->getMessage()];
            dd($msg);
            Log::channel('daily')->error(json_encode($msg));
        }

        return $potential;


    }

    /**
     * Undocumented function
     *
     * @param [type] $info
     * @param string $module
     * @return void
     */
    function createInfo($info,$module='Potentials'){

            try {

                $saved = $this->vt->entities->createOne($module, $info);
                Log::channel('daily')->error('Saved'.$module.' '.$saved['id']);
                return $saved;

            } catch (\Exception $e) {

                $msg = ['method' => 'createInfo', 'data' => ['module' => $module], 'error' => $e->getMessage()];
                Log::channel('daily')->error(json_encode($msg));
                return array('error' => 'Error creating '.$module.' '.$e->getMessage());

            }
    }





    /**
     * Undocumented function
     *
     * @param [type] $id
     * @param [type] $module
     * @return void
     */
    function getInfobyID($id, $module){

        $info = null;
        try {

            $info = $this->vt->entities->findOneByID($module, $id);
            

        } catch (\Exception $e) {

            $msg = ['method' => 'getInfobyID', 'data' => ['module' => $module, 'id' => $id], 'error' => $e->getMessage()];
            Log::channel('daily')->error(json_encode($msg));


        }

        return $info;
    }



    /**
     * Undocumented function
     *
     * @param [type] $module
     * @param [type] $search
     * @return void
     */
    public function findMultiple($module,$search){

        $info = null;
        try {

            $info =$this->vt->entities->findMany($module,$search);

        } catch (\Exception $e) {

            $msg = ['method' => 'findMultiple', 'data' => ['module' => $module, 'search' => $search], 'error' => $e->getMessage()];
            Log::channel('daily')->error(json_encode($msg));


        }

        return $info;
    }



    /**
     * Undocumented function
     *
     * @param [type] $email
     * @return void
     */
    public function searchUser($email){
        
        $info = null;

        try{
                    
            if($email != '')
                $info = $this->vt->entities->findOne('Users',['email1' => $email]);
    
            if($info == null ){
                $info = $this->vt->entities->findOne('Users',['email1' =>'yarosemena03@gmail.com']);  
            }
            
        }catch (\Exception $e) {
            Log::channel('daily')->error($e->getMessage());
        }
        
        return $info;
    }



    /**
     * Undocumented function
     *
     * @param [type] $searchContact
     * @param [type] $searchPot
     * @return void
     */
    public function rules($searchContact,$searchPot,$type){


        try{

        $resultContacts = null;
        $resultPot      = null;

        $totalContact   = count($searchContact);
        $totalPot       = count($searchPot);


        $rules = array();

        $types = [  '4' => 'Id  de la oportunidad existe pero no coincide con id de cliente',
                    '5' => 'El cliente existe y tiene varias oportunidades cuyo id coincide', 
                    '6' => 'El id del cliente y oportunidad  se repiten' ];


 
        $resultContacts = array();

        if($type == 'health'){
            $resultContacts = $this->healthcontactSearchRules($searchContact);
        }else{
            $resultContacts = $this->findMultiple('Contacts',$searchContact);
        }
       
        if($totalPot > 0 ){
            $resultPot = $this->findMultiple('Potentials',$searchPot);
        }




        if($resultContacts == null && $resultPot == null){

              $rules = array(

                'status'    => 3,
                'type'      => '',
                'contact'   => $resultContacts,
                'potential' => $resultPot,
              ) ;
            
        }else{

            if(is_array($resultContacts)){
                $totalContacts       = count($resultContacts);
           }else
                $totalContacts       = 0;


           if(is_array($resultPot)){
                $totalPot       = count($resultPot);
           }else
                $totalPot       = 0;





            Log::channel('daily')->error('Contacts '.$totalContacts.' Pot '.$totalPot);

            if($totalContacts >0){

                 $rules = array(

                    'status'    => 7,
                    'type'      => 'Contacto Repetido',
                    'contact'   => null,
                    'potential' => null,
                ) ;

            }


            if($totalContacts == 1 && ($resultPot == null ) ){

                $rules = array(

                    'status'    => 2,
                    'type'      => '',
                    'contact'   => $resultContacts[0],
                    'potential' => null,
                ) ;
            }

            if($totalContacts == 1 && $totalPot == 1){


                if ($resultContacts[0]['id'] == $resultPot[0]['contact_id']){

                    $rules = array(

                        'status'    => 1,
                        'type'      => '',
                        'contact'   => $resultContacts[0],
                        'potential' => $resultPot[0],
                    ) ;

                }elseif($resultContacts[0]['id'] != $resultPot[0]['contact_id']){


                    $rules = array(

                        'status'    => 4,
                        'type'      => $types[4],
                        'contact'   => null,
                        'potential' => null,
                    ) ;

                }
                
            }

            if($totalContacts == 0 && $totalPot > 0){


                $rules = array(

                    'status'    => 4,
                    'type'      => $types[4],
                    'contact'   => null,
                    'potential' => null,
                ) ;


            }

            if($totalContacts == 1 && $totalPot > 1){


                $rules = array(

                    'status'    => 5,
                    'type'      => $types[5],
                    'contact'   => null,
                    'potential' => null,
                ) ;


            }


            if($totalContacts > 1 && $totalPot > 1){


                $rules = array(

                    'status'    => 6,
                    'type'      => $types[6],
                    'contact'   => null,
                    'potential' => null,
                ) ;


            }



        }
        }catch(\Exception $e){

            $msg = ['method' => 'healthsherpaRules',  'error' => $e->getMessage()];
            Log::channel('daily')->error(json_encode($msg));
        }


        return $rules;

    
    }


    /**
     * Undocumented function
     *
     * @param [type] $searchContact
     * @param [type] $searchPot
     * @return void
     */
    public function healthcontactSearchRules($searchContact){

        $totalContact   = count($searchContact);
        Log::channel('daily')->error(json_encode($searchContact));

        
        $resultContacts = null;
        
        try{
        
        if($totalContact >0){
            $resultFull =  $this->findMultiple('Contacts',$searchContact);

            
        
            if(array_key_exists('birthday',$searchContact)){
                
                
                $searchtwo      = array('birthday' => $searchContact['birthday'],'email'=> $searchContact['email']);
                $searchthree    = array('birthday' => $searchContact['birthday'],'cf_1856'=> $searchContact['cf_1856']);

                if($resultFull == null){
                    
                    $resultEmail =  $this->findMultiple('Contacts',$searchtwo);

                    if($resultEmail == null){

                        $resultPhone =  $this->findMultiple('Contacts',$searchthree);

                        if($resultPhone != null){
                            $resultContacts = $resultPhone;
                        }

                    }else{

                        $resultContacts = $resultEmail;
                    }

                }else{
                    
                    $resultContacts = $resultFull;
                }
            }
        }
        
        }catch(\Exception $e){

            $msg = ['method' => 'healthcontactSearchRules',  'error' => $e->getMessage()];
            Log::channel('daily')->error(json_encode($msg));
            
        }

        return $resultContacts;
        

    }


}
