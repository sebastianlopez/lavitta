<?php

namespace App\Http\Controllers;

use App\Imports\HealthImport;
use App\Models\Datacrm;
use App\Models\Mapping;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{

    private $datacrm;
    private $mailler;
    private $mapping_route;
    private $file_route;


    private $contacts;

    public function __construct(Datacrm $crm, MailController $mail){

        $this->datacrm = $crm;
        $this->mapping_route  = 'app/public/mapping/';
        $this->file_route     = 'app/public/uploads/';
        $this->mailler        = $mail;

        $this->contacts = array(

            0 => 'Space ( )',
            1 => 'Line ( - )',
            2 => 'Underline ( _ )',
            3 => "No Space "
        );

    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function index($case=''){

      $reg['case'] = $case;
      $reg['show'] = true;

      if($case != '')  
          $reg['reg'] = $info = $this->datacrm->getCase($case);
        else
            return redirect('https://lavitainsurancetax.com/');
        
      
      if(isset($info['cf_1662']) > 0 ){
            $reg['show'] = false;
      }

      return view('form',$reg);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function saveForm(Request $request){

        
        $case = $request->case;
        $values = $this->datacrm->getCase($case);

      
        $values['cf_1662'] = $score = $request->score;
        $values['cf_1664'] = $request->comments;

        $helpdesk = $this->datacrm->updateCase($values);

        
    } 

    /**
     * Undocumented function
     *
     * @return void
     */
    public function mapHealth(){

        $reg = array();

        $reg['show'] = 0;
       // $this->household();
        $reg['mapp'] = Mapping::where('save_mapping','1')->pluck('fake_name','id');

        $reg['fileName'] = null;
        
        return view('health',$reg);
    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function loadImport(Request $request){


       /* $request->validate([
            'exportto' => 'required|mimes:csv,xlx,xls'
            ]);
            */
        // id of mapping selected    
        $saved_map       = $request->mapping;
        $reg['type']     = $request->type;
        $reg['email']    = $request->email;
            
        if($request->file()) {

            $fileName = time().'_'.$request->exportto->getClientOriginalName();
            $reg['fake']     = $request->exportto->getClientOriginalName();
            request()->file('exportto')->storeAs('uploads', $fileName, 'public');
            
        }else{

            return redirect('load_file');

        }


        $reg['load_map'] = array();

        if($saved_map != ''){

            $maped = Mapping::where('id',$saved_map)->first();

            $file = file_get_contents( storage_path($this->mapping_route.$maped['mapping']),true);
            $reg['load_map'] = json_decode($file, true);
            $reg['mapping_selected'] = $saved_map;
        }

      
        $reg['show'] = 1;

        $reg['fileName'] = $fileName;
        $collection      = Excel::toCollection(new HealthImport, storage_path($this->file_route.$fileName)); 
        $headers         = $collection[0]->toArray();

        $reg['excelloptions'] = array_filter($headers['0']);
      
    
        $potentials = $this->datacrm->getFields('Potentials');
        $contacts   = $this->datacrm->getFields('Contacts');


        $pot = array();

     
        foreach($potentials['fields'] as $out){
            if( $out['label'] != 'potentialid' &&  $out['type']['name'] != 'reference' &&  $out['label'] !='createdtime')
                $pot['pot_'.$out['type']['name'].'_'.$out['name']] = '(Opportunities) '.$out['label'];
           
        }
   
        $cli = array();
        foreach($contacts['fields'] as $out){
            if( $out['label'] != 'contactid' &&  $out['type']['name'] != 'reference' &&  $out['label'] !='createdtime')
                $cli['con_'.$out['type']['name'].'_'.$out['name']] = '(Clients) '.$out['label'];
           
        }

        asort($pot);
        asort($cli);

        $reg['potentials'] = $pot;
        $reg['clients']    = $cli; 

        
        $reg['contacts'] = $this->contacts;

        return view('health',$reg);
    }


    /** */
    public function test(){

        $contactsearch = array('birthday' => '1982-10-06', 'email' => 'mauricioromero605@gmail.com','mobile' => '17865014415');
        $potentsearch = array('cf_1094' => '2024-01-01','cf_1712' => '18471121');

        $type = 'health';

        $rules = $this->datacrm->rules($contactsearch,$potentsearch,$type);

       
        
        $contactsearch = array('birthday' => '1983-08-23', 'email' => 'mrjohnj6@gmail.com','mobile' => '17865399923');
        $potentsearch = array('cf_1094' => '2024-01-01','cf_1712' => '11808949');


        $rules2 = $this->datacrm->rules($contactsearch,$potentsearch,$type);


         
        $contactsearch = array('birthday' => '1990-10-07', 'email' => 'claudia_acosta@hotmail.es','mobile' => '13059226471');
        $potentsearch = array('cf_1094' => '2024-01-01','cf_1712' => '15094228');


        $rules3 = $this->datacrm->rules($contactsearch,$potentsearch,$type);


        $contactsearch = array('birthday' => '1972-05-07', 'email' => 'kevinf2310@gmail.com','mobile' => '17863974394');
        $potentsearch = array('cf_1094' => '2023-12-01','cf_1712' => '19399035');

        
        $rules4 = $this->datacrm->rules($contactsearch,$potentsearch,$type);

        $contactsearch = array('birthday' => '1972-05-07', 'email' => 'kevinf2310@gmail.com','mobile' => '17863974394');
        $potentsearch = array('cf_1094' => '2024-02-01','cf_1712' => '19399035');

   
        $rules5 = $this->datacrm->rules($contactsearch,$potentsearch,$type);


        $contactsearch = array('birthday' => '1972-05-07', 'email' => 'kevinf2310@gmail.com','mobile' => '17863974394');
        $potentsearch = array('cf_1094' => '2024-02-01','cf_1712' => '4339876');

   
        $rule6 = $this->datacrm->rules($contactsearch,$potentsearch,$type);


      

        dd($rules,$rules2,$rules3,$rules4,$rules5,$rule6);
    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function saveMapping(Request $request){


        /** Mapping Parameters */
        $mapping    = $request->mapping;
        $excell     = $request->excell;
        $reference  = $request->reference;
        $concat     = $request->concat;

        /** Extra Parameters */
        $filename   = $request->filename;
        $id_map     = $request->id_map;
        $email      = $request->email;
        $savemap    = $request->savemap;
        $type       = $request->type;
        $mapname    = $request->mapname;
     
        $i = 0;
        foreach( $excell as  $out ){

            $field = $mapping[$i];

            if($field != ''){

                $info_field = explode('_',$field, 3);

                $map[$i]['module']      = $info_field[0];
                $map[$i]['type']        = $info_field[1];
                $map[$i]['field']       = $info_field[2];
                $map[$i]['reference']   = (isset($reference[$i])) ? '1':'0';

                if(isset($concat[$i]))
                    $map[$i]['concat']  = $concat[$i];
                else
                    $map[$i]['concat']      = null;

                $map[$i]['excel']   = implode(',', $out);
                
            }
            $i++;
        
        }

        $namemapping = date('Ymdhis').'.txt';
        $file = fopen( storage_path($this->mapping_route.$namemapping ), "w"  );
        fwrite($file, json_encode($map));
        fclose($file);
        
        $import['filename']       = $filename;
        $import['mapping']        = $namemapping ;
        $import['status']         = 'pending';
        $import['type']           = $type;
        $import['email']          = $email;
        $import['save_mapping']   = $savemap;
        $import['fake_name']      = $mapname;  
        

        $created = Mapping::create($import);
        

        return response()->json($created, 200);

    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function addline($id){

            return '<tr id="line'.$id.'">
            <th>'. \Form::select('mapping[]',[],'',array('class'=>'form-select select2','placeholder'=>'Select an Option','id'=>'mapping'.$id)) .'</th>
            <td>'. \Form::select('excell['.$id.'][]',[],'',array('class'=>'3col active form-select','data-live-search'=>'true','multiple'=>'multiple','aria-label'=>'','id'=>'excell'.$id)) .'</td>
            <td class="">'.\Form::checkbox('reference['.$id.']',$id ).'</td>
            <td >'.\Form::select('concat['.$id.']',$this->contacts,'',array('class'=>'form-select datacrm inline','placeholder'=>'Select an Option','disabled'=>'disabled','id'=>'concat'.$id)) .' </td>
            <td ><button type="button" class="btn btn-danger inline" onclick="deleteLine(\''.$id.'\')">Remove</button></td></tr>';
    }


    /**
     * Undocumented function
     *
     * @param [type] $type
     * @return void
     */
    public function getMapping($type){

        $mapping = Mapping::where('type',$type)->where('save_mapping',1)->pluck('fake_name','id');

        return response()->json($mapping, 200);

    }


    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function deleteMapping(Request $request){

        $id = $request->id;
        $type = $request->type;


        Mapping::where('id',$id)->update(['save_mapping'=>0]);


        $mapping = Mapping::where('type',$type)->where('save_mapping',1)->pluck('fake_name','id');
        return response()->json($mapping, 200);
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function household(Request $request){

        $id = $request->id;
        $potential = $this->datacrm->getInfobyID($id,'Potentials');


        $household = array('cf_1732','cf_1758','cf_1768','cf_1778','cf_1788','cf_1798','cf_1808','cf_1818');
        $househdob = array('cf_1740','cf_1764','cf_1774','cf_1784','cf_1798','cf_1804','cf_1814','cf_1824');
        $househgen = array('cf_1738','cf_1762','cf_1772','cf_1782','cf_1792','cf_1802','cf_1812','cf_1822');
        $househapl = array('cf_1742','cf_1766','cf_1776','cf_1786','cf_1796','cf_1806','cf_1816','cf_1826');


        for($i=0; $i<count($househdob); $i++) {

            if($potential[$household[$i]] != ''){

                $info = $this->datacrm->getInfobyID($potential['contact_id'],'Contacts');

                $search = array('lastname'=>$potential[$household[$i]],'birthday'=>$potential[$househdob[$i]],'email'=>$info['email'],'mobile'=>$info['mobile']); 
                $result = $this->datacrm->findMultiple('Contacts',$search); 

                if($result == null){

                    $cli['lastname'] = $potential[$household[$i]];
                    $cli['birthday'] = $potential[$househdob[$i]];
                    $cli['cf_1431']  = $potential[$househgen[$i]];


                    $cli['email']                = $info['email'];
                    $cli['mobile']               = $info['mobile'];
                    $cli['cf_1419']              = 'Household member';
                    $cli['cf_1423']              = 'Household member';
                    $cli['cf_1479']              = $info['cf_1479'];
                    $cli['cf_1493']              = $info['lastname'];
                    $cli['cf_1399']              = $info['cf_1399'];  
                    $cli['assigned_user_id']     = $info['assigned_user_id'];
                    $cli['cf_1397']              = $info['cf_1397'];
                    $cli['mailingcountry']       = $info['mailingcountry'];
                    $cli['mailingcity']          = $info['mailingcity'];
                    $cli['mailingcountry']       = $info['mailingcountry'];


            
                    $contact = $this->datacrm->createInfo($cli,'Contacts');

                    $pot = array(
                        'contact_id'           => $contact['id'],
                        'potentialname_pick'   => $potential['potentialname_pick'],
                        'potentialname'        => $potential['potentialname'],
                        'cf_1082'              => 'Active',
                        'closingdate'          => $potential['closingdate'],
                        'sales_stage'          => 'Closed Won',
                        'assigned_user_id'     => $potential['assigned_user_id'],
                        'cf_1204'              => $potential['cf_1204'],
                        'cf_1094'              => $potential['cf_1094'],
                        'cf_1074'              => $potential['cf_1074'],
                        'cf_1092'              => $potential['cf_1092'],
                        'cf_1708'              => $potential['cf_1708'], 
                        'cf_1710'              => $potential['cf_1710'], 
                        'cf_1716'              => $potential['cf_1716'], 
                        'cf_1718'              => $potential['cf_1718'], 
                        'cf_1720'              => $potential['cf_1720'], 
                        'cf_1722'              => $potential['cf_1722'], 
                        'cf_1724'              => $potential['cf_1724'], 
                        'cf_1726'              => $potential['cf_1726'], 
                        'cf_1728'              => $potential['cf_1728'],
                        'cf_1164'              => $potential['cf_1164'],
                        'cf_1706'              => $potential['cf_1706'],
                        'cf_1698'              => $potential['cf_1698'],
                        'sector'               => $potential['sector'],
                        'cf_1505'              => 1,
                        'cf_1710'              => $potential['cf_1710'],
                        'cf_1708'              => $potential['cf_1708'],

                    );


                    $this->datacrm->createInfo($pot,'Potentials');
                }   

            }


        }

        return true;

    }
    

}
