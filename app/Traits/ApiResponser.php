<?php

namespace App\Traits;

trait ApiResponser{

	protected function success($data, $message = null, $code = 200)
	{
		return response()->json([
			'status'=> 'Success',
			'message' => $message,
			'data' => $data
		], $code);
	}

	protected function error($message = null, $code)
	{
		return response()->json([
			'status'=>'Error',
			'message' => $message,
			'data' => null
		], $code);
	}

}