<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Providers\AddMessageApi;
use GuzzleHttp\Exception\ClientException;
use App\Models\Message;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CheckParamsRequest as Request;

class ApiController extends Controller
{
    public function api(Request $request)
    {

        $this->saveTicket($request);

        return response()->json('{"response":"OK"}', 200, ['Content-Type' => 'application/json; charset=UTF-8']);

    }

    public function requestUser($login,$password)
    {

        $client = new \GuzzleHttp\Client();

        try {
            $res = $client->request('POST', 'https://reqres.in/api/users',
                ['name' => $login,
                    'job' => $password]);

            if ($res->getStatusCode() == 201) {
                $array = json_decode($res->getBody()->getContents());

                Log::info('Hoмер пользователя: '.$array->id.' Дата создания: '.$array->createdAt);
            }
        } catch
        (ClientException $exception) {
            Log::debug('Exception while sending payments - not save');
        }

    }

    public function saveTicket($request)
    {
        foreach ($request['ticket'] as $parseTicket){
            $ticket = new Ticket();
            $ticket->uid = htmlspecialchars(strip_tags($parseTicket['uid'],ENT_QUOTES));
            $ticket->subject = htmlspecialchars(strip_tags($parseTicket['subject'],ENT_QUOTES));
            $ticket->user_name = htmlspecialchars(strip_tags($parseTicket['user_name'],ENT_QUOTES));
            $ticket->user_email = htmlspecialchars(strip_tags($parseTicket['user_email'],ENT_QUOTES));

            $ticket->created_at = htmlspecialchars(strip_tags($parseTicket['created_at'],ENT_QUOTES));
            $ticket->updated_at = htmlspecialchars(strip_tags($parseTicket['updated_at'],ENT_QUOTES));
            $ticket->save();
            $ticket_id[] = $ticket->id;
        }

        $this->saveMessage($request,$ticket_id);

    }

    public function saveMessage($request,array $ticket_id)
    {
        foreach ($request['send'] as $parseMessage) {
            foreach ($ticket_id as $ticket_id_first){
                $message = new Message();
                $message->ticket_id = htmlspecialchars(strip_tags($ticket_id_first,ENT_QUOTES));
                $message->author = htmlspecialchars(strip_tags($parseMessage['author'],ENT_QUOTES));
                $message->content = htmlspecialchars(strip_tags($parseMessage['content'],ENT_QUOTES));

                $message->created_at = htmlspecialchars(strip_tags($parseMessage['created_at'],ENT_QUOTES));
                $message->updated_at = htmlspecialchars(strip_tags($parseMessage['updated_at'],ENT_QUOTES));
                $message->save();
                event(new AddMessageApi($message, $request['credentials']));
            }
        }

    }

}
