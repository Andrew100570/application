<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Providers\AddMessage;
use Illuminate\Support\Str;
use App\Models\Message;
use App\Models\ServerCredential;
use App\Http\Requests\WordRequest;

class AdminController extends Controller
{

    public function index()
    {
        return view('admin');
    }

    public function addTicket(WordRequest $request)
    {
        //проверка на маты.В нашем случае просто плохие слова
        $bads = ['дурачок','долбоящер','лохопед'];
        $word = strip_tags($request->all()['user_name']);
        $word = htmlspecialchars($word, ENT_QUOTES);

        foreach ($bads as $bad){
            if($word == $bad){
                if($word == 'долбоящер'){
                    return view('admin', ['succes' => 'Уважай живые существа,исправь значение "user_name"!!!']);
                }else{
                    return view('admin', ['succes' => 'Не используйте плохие слова для поля "user_name"!!!']);
                }
            }
        }


        return view('admin', ['succes' => 'Письмо отправлено пользователю']);
    }

    public function ticket()
    {
        $ticket = Ticket::all();
        $message = Message::all();
        $credentials = ServerCredential::all();

        return view('ticket', ['tickets' => $ticket, 'messages' => $message, 'credentials' => $credentials]);
    }

    public function saveTicket($request)
    {
        $ticket = new Ticket();

        $ticket->uid = Str::uuid()->toString();
        $ticket->subject    = $request->all()['subject'];
        $ticket->user_name  = $request->all()['user_name'];
        $ticket->user_email = $request->all()['email'];

        $ticket->created_at = now();
        $ticket->updated_at = now();

        $ticket->save();

        $ticket_id = $ticket->id;

        $this->saveMessage($request, $ticket_id);

    }

    public function saveMessage($request, $ticket_id)
    {
        foreach ($request->all()['author'] as $key_author => $author) {
            foreach ($request->all()['content'] as $key_cont => $content) {
                if ($key_author == $key_cont) {

                    $message = new Message();

                    $message->ticket_id = $ticket_id;
                    $message->author = $author;
                    $message->content = $content;

                    $message->created_at = now();
                    $message->updated_at = now();

                    $message->save();

                }
            }
        }
        event(new AddMessage($message,$request));

    }


}
