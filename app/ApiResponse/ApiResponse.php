<?php

namespace App\ApiResponse;

class ApiResponse
{
    private int $status=200;
    private ?string $message=null;
    private ?string $token=null;
    private mixed $data=null;
    private array $append=[];

    public function setMessage($message): void
    {
        $this->message=$message;
    }
    public function setToken($token): void
    {
        $this->token=$token;
    }

    public function setStatus($status): void
    {
        $this->status=$status;
    }

    public function setData($data): void
    {
        $this->data=$data;
    }

    public function setAppend($append): void
    {
        $this->append=$append;
    }

    public function response(): \Illuminate\Http\JsonResponse
    {
        $body=[];
        !is_null($this->message) && $body['message']=$this->message;
        !is_null($this->data) && $body['data']=$this->data;
        !is_null($this->token) && $body['token']=$this->token;
        $body=$body+$this->append;
        return response()->json($body, $this->status);
    }
}
