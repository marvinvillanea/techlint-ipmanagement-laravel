<?php

namespace App\Actions;

class ProcessAPI
{

    public function execute($request, $controller, $action): array
    {

        // dispatch to middleware
        // $result = ValidatedReqExecuteReq::validatedExecute( $controller,  $action, $request->all());
        //API to microservice then returrn response and dispatch update and insert 
        $result = [
            "data" => '',
            'msg' => 'Success',
            'code' => '200'
        ];

        return $result;
    }
}
