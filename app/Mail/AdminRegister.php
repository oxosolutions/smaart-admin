<?php

namespace App\Mail;

use App\Ministrie;
use App\Department;
use App\Designation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminRegister extends Mailable
{
    use Queueable, SerializesModels;

    protected $userDetails;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userDet)
    {
        $this->userDetails = $userDet;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $ministry = Ministrie::where('id',$this->userDetails['ministries'])->get();
        $department = Department::where('id',$this->userDetails['department'])->get();
        $designation = Designation::where('id',$this->userDetails['designation'])->get();
        foreach ($ministry as $key => $value) {
           $minisrty = $value->ministry_title;
        }
        foreach ($department as $key => $value) {
           $department = $value;
        }
         foreach ($designation as $key => $value) {
           $designation = $value;
        }
        return $this->view('mail.aregister',[
                                                'user'          =>$this->userDetails['name'],
                                                'api_token'     =>$this->userDetails['api_token'],
                                                'desc'          =>$this->userDetails['description'],
                                                'userName'      =>$this->userDetails['name'],
                                                'userEmail'     =>$this->userDetails['email'],
                                                'userPhone'     =>$this->userDetails['phone'],
                                                'ministry'      =>json_decode($ministry)[0]->ministry_title,
                                                'designation'   =>json_decode($designation)->designation,
                                                'department'    =>json_decode($department)->dep_name
                                          ])       
                    ->subject($this->userDetails['subject']);
    }
}
