<?
require_once "HTTP/Request.php";

$req =& new HTTP_Request("http://google.com/");
if (!PEAR::isError($req->sendRequest())) {
  echo $req->getResponseBody();
} else {
  echo "HTTP ERROR";  
}

?>
