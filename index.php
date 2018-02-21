<html>
<?php
/**
 *  Author BRAHIMI 
 */
session_start();
 require 'config.php';
 require 'GoogleContact.php';
 
  
?>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<title>Export Contact</title>

</head>
<body>
 <a  class="btn btn-primary" href="<?php echo Redirection_URL ?>?delete_session=true">Disconect</a>
<?php 
  
   $contact = new GoogleContact();

    $contact->setCustomerKey(Client_ID);
    $contact->setCustomerSecret(Client_SECRET);
    $contact->setRedirectUrl(Redirection_URL);
    $contact->setScope('https://www.google.com/m8/feeds/');

    if (isset($_REQUEST['logout']) || isset($_GET['delete_session'])) {
        unset($_SESSION['access_token']);
        unset($_GET['code']);
    }
    if (isset($_GET['code'])) {
        echo 'code';
        $contact->setAuthCode($_GET['code']);
        $response = $contact->curl_var_init_access();
        $contact->setAccessToken($response->access_token);
        $_SESSION['access_token'] = $contact->getAccessToken();
        header('location: '.$contact->getRedirectUrl());
    }
    if(!isset($_SESSION['access_token'])){
        
     $url = $contact->createInitUrl();
  
     ?>
    <a href=<?php echo $url ?>>Import mes contacts gmail<img src="images/sign1.png" alt="" id="signimg"/></a>
     
     <?php
    }else{
        $contact->setAccessToken($_SESSION['access_token']);
        $contacts = $contact->getAllContacts();
       
      echo '<table class="table table-dark">'.
                '<thead>'.
                    '<tr>'.
                      '<th scope="col">#</th>'.
                      '<th scope="col">Name</th>'.
                      '<th scope="col">Email</th>'.
                    '</tr>'.
                '</thead>'.
                '<tbody>';
        foreach($contacts as $cct){
            
            echo '<tr><td></td><td>'.$cct['name'].'</td><td>'.$cct['email'].'</td></tr>';
        }
      echo  '</tbody>'.
           '</table>';
                    

    }
  

    
  
     

?>
</div>
</center>
</div>
</div>
</div>

</body>
</html>