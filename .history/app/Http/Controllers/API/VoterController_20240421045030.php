<?php


namespace App\Http\Controllers\API;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once 'vendor/autoload.php';

use App\Http\Controllers\Controller;
use App\Models\API\tblotp;
use App\Models\API\tblvoter;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    public function registerVoter(Request $request)
    {
        $request->validate([
            'voterID' => 'required|string|max:50',
            'fullname' => 'required|max:59',
            'email' => 'required|email|max:60|unique:tblvoters',
            'maritalstatus' => 'required|string|max:12',
            'sex' => 'required|string|max:10',
            'DOB' => 'required|string|max:25',
            'phone' => 'required|string|max:11',
            'address' => 'required|string|max:60',
            'lga' => 'string|max:40',
            'state' => 'string|max:30',
            'occupation' => 'required|string|max:40',
            'status' => 'required|string|max:1',
            'educational_qualification' => 'required|string|max:10',
            'photo' => 'required|string|max:5000',
        ]);

        $voter = tblvoter::create([
            'voterID' => ucwords($request->voterID),
            'fullname' => ucwords($request->fullname), 
            'email' => ucwords($request->email),
            'maritalstatus' => ucwords($request->maritalstatus), 
            'sex' => ucwords($request->sex),
            'DOB' => ucwords($request->DOB), 
            'phone' => ucwords($request->phone),
            'address' => ucwords($request->address), 
            'lga' => ucwords($request->lga),
            'state' => ucwords($request->state), 
            'occupation' => ucwords($request->occupation),
            'status' => ucwords($request->status), 
            'educational_qualification' => ucwords($request->educational_qualification),
            'photo' => ucwords($request->photo), 
            
            ]);

            $email =  $voter->email;

        //send otp via SMS
        $username = 'info.autosyst@gmail.comx'; //Note: urlencodemust be added forusernameand
        $password = 'Integax.sms@2022x'; // passwordas encryption code for security purpose.
        $senderID = 'MechanicHub';
        $fullname =  $voter->fullname;

        $otp = rand(10200, 90002);

        $message = 'Hello, ' . $fullname . ' Your OTP to complete your registration is ' . $otp . '. Please Do not share with anybody';
        $api_url  = 'https://portal.nigeriabulksms.com/api/';
        $data = array('username' => $username, 'password' => $password, 'sender' => $senderID, 'message' => $message, 'mobiles' => $voter->phone);
        $data = http_build_query($data);
        $request = $api_url . '?' . $data;
        $result  = file_get_contents($request);
        $result  = json_decode($result);

//send otp email
$appname="Secured Mobile-based E-voting System using 2FA security";  
$email_server="SMTP.GMAIL.COM";
$email_username="ADMISSION.MANSENSHS@GMAIL.COM";
$app_password="XMVLDREPYHGKJFKF";
$port=465;
$email_website = "ADMISSION.MANSENSHS@GMAIL.COM";

$mail = new PHPMailer(true);
     
//Server settings
$mail->isSMTP();                                            //Send using SMTP
$mail->Host       = $email_server;                     //Set the SMTP server to send through
$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
$mail->Username   = $email_username;                     //SMTP username
$mail->Password   = $app_password;                               //SMTP password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
 $mail->Port       = $port;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

//Recipients
$mail->setFrom($email_website, $appname);
$mail->addAddress($email,$fullname);     //Add a recipient

$message = "
<html>
<head>
<title>OTP |$appname </title>
</head>
<body>
<p>Hello $fullname ,</p>
       
<p>  Your OTP code is :$otp  .</p>

<p>$appname</p>        
</body>
</html>
";

//Content
$mail->isHTML(true);                                  //Set email format to HTML
$mail->Subject = 'OTP | '.$appname.'';
$mail->Body    = $message;
$mail->send();

        //Add to OTP table
        tblotp::create([
            'code' => $otp,
            'voterID' => $voter->voterID,

        ]);
        //End of otp table

        $token = $voter->createToken('register')->plainTextToken;

        return response()->json(['success' => true, 'message' => 'OTP sent to your Phone and Email', 'token' => $token], 201);
    }

    public function validateLoginOTP(Request $request)
    {
        $otp = $request->code;
        $voterID = $request->voterID;

        // Get the OTP record for the user from the database
        $otpRecord = tblotp::where('code', $otp)
            ->where('phone', $phone)
            ->where('created_at', '>=', now()->subMinutes(50)) // Only consider OTPs created in the last 15 minutes
            ->first();
        // Check if the OTP record exists and has not expired
        if ($otpRecord) {
            // If the OTP is valid, delete the record from the database
             $otpRecord->delete();

            // Update the user status in the customer_mechanics table
            DB::update('update customer_mechanics set status = ? where phone = ?', ["1", $phone]);

            $userRecord = customer_mechanic::where('phone', $phone)->first();
            
            //send email
            Mail::send(new WelcomeEmail($userRecord));

            //Add to activity log
            activity_log::create([
                'task' => $userRecord->fullname . ' validated and completed his registration on ' . NOW(),
            ]);


            $token = $otpRecord->createToken('mechanic_hub')->plainTextToken;
            // Return a success response
            return response()->json(['success' => true, 'message' => 'Your registration is Now complete', 'token' => $token], 201);
        } else {
            // Return an error response
            return response()->json(['success' => false, 'message' => 'Invalid OTP or OTP has expired'], 401);
        }
    }
}
