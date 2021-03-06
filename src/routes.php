<?php


use Slim\Http\Request;
use Slim\Http\Response;

 
use Firebase\JWT\JWT;
use Tuupola\Base62;
 
$app->post("/token",  function ($request, $response, $args) use ($container){
    /* Here generate and return JWT to the client. */
    //$valid_scopes = ["read", "write", "delete"]
 
  	$requested_scopes = $request->getParsedBody() ?: [];
 
    $now = new DateTime();
    $future = new DateTime("+10 minutes");
    $server = $request->getServerParams();
    $jti = (new Base62)->encode(random_bytes(16));
    $payload = [
        "iat" => $now->getTimeStamp(),
        "exp" => $future->getTimeStamp(),
        "jti" => $jti,
    ];
    $secret = JWT_SECRET;
    $token = JWT::encode($payload, $secret, "HS256");
    $data["token"] = $token;
    $data["expires"] = $future->getTimeStamp();
    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});
 
$app->get("/not-secure",  function ($request, $response, $args) {
 
    $data = ["status" => 1, 'msg' => "No need of token to access me"];
 
    return $response->withStatus(200)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});
 
 
$app->get('/home', function ($request, $response, $args) {
        // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");
 
    // Render index view
    return $this->renderer->render($response, 'index.phtml', ["name" => "Welcome to Starter Token Base Auth by Tanutsun"]);
});


/*
*
* part must use jwt auth
*
*/

$app->post("/".VERSION."/formData",  function ($request, $response, $args) {
    
    return include ("../".VERSION."/formData.php");

});

$app->get("/".VERSION."/secure",  function ($request, $response, $args) {
    
        return include ("../".VERSION."/secure.php");
    
});