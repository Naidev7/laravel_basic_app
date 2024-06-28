<?php

namespace App\Listeners;

use App\Events\ChirpCreated;
use App\Models\User;
use App\Notifications\NewChirp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendChirpCreatedNotifications implements ShouldQueue
{
    /* Implement de la clase ShouldQueu ya queremos que esta clase oyente se comporte de manera asyncrona, es decir, que se coloque esta clase como si fuera un work que tiene que ponerse a la cola de otra acción (la creación de unn nuevo chirp y por resultante la creación de un nnuevo event), el hecho de querer que sea async implica que esta clase es el work que se debe realizar, que implementa osea que utiliza la interfaz de las colas y que por lo tanto con el comando php artisan queu:work. Si quiero que sea una tarea sinncrona, entonces no hace falta implementar esta ni hacer el comando */

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ChirpCreated $event): void
    {
        foreach (User::whereNot('id', $event->chirp->user_id)->cursor() as $user) {
            $user->notify(new NewChirp($event->chirp));
        }
    }
}
