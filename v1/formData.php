<?php 

    $data = $request->getParsedBody();

   $result = ["status" => 1, 'msg' => $data];

   // Request with status response
   return $this->response->withJson($result, 200);

?>