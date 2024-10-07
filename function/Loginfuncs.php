<?PHP 

function sschk(){
    if(!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"]!=session_id()){
      exit("Login Error");
    }else{
      session_regenerate_id(true);
      $_SESSION["chk_ssid"] = session_id();
    }
  }

  function Alert($scr){
    $alert = "<script type='text/javascript'>alert('$scr');</script>";
    echo $alert;
    $referer = $_SERVER['HTTP_REFERER'];
    echo "<script>location.href = '$referer';</script>";
  }

?>