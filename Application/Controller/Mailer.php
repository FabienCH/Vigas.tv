<?php
namespace Vigas\Application\Controller;

require_once __DIR__.'/../../Vendor/PHPMailer/PHPMailerAutoload.php';

/**
 * Class Mailer extends PHPmailer.
 * Builds and sends email
 */
class Mailer extends \PHPmailer
{
    /**
    * @param string $From Sender email address
    * @param string $FromName Sender display name
    * @param string $Host SMTP host
    * @param string $Subject Email subject
    * @param string $Body Email body
    * @param string $AddAddress Recipient email address
    * @param array $smtp_conf SMTP server informations
    */
    public function __construct($From, $FromName, $Host, $Subject, $Body, $AddAddress, $smtp_conf)
    {
        $this->From = $From;
        $this->FromName = $FromName;
        $this->IsSMTP();
        $this->Host = $Host;
        $this->SMTPAuth = true;
        $this->SMTPSecure = $smtp_conf['secure'];
        $this->Port = $smtp_conf['port'];
        $this->Username = $smtp_conf['username'];
        $this->Password = $smtp_conf['password'];
        $this->Subject = $Subject;
        $this->Body = $Body;
        $this->AddAddress($AddAddress);		
    }

    /** 
    * @return mixed Returns true if email has been sent, error informations otherwise
    */
    public function sendMail()
    {
        if($this->Send())
        {
            return true;
        } 
        else
        {	
            return $this->ErrorInfo;
        }
    }
}

