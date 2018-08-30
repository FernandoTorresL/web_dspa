<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserRegisteredSuccessfully extends Notification
{
    use Queueable;
    /**
     * @var User
     */
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        /** @var User $user */
        $user = $this->user;

        return (new MailMessage)
            ->from(env('ADMIN_MAIL'))
            ->replyTo('fernando.torresl@imss.gob.mx', 'Fernando Torres Laguna')
            ->subject('¡Registro de cuenta exitoso!')
            ->greeting(sprintf('Hola %s', $user->name))
            ->line('Te has registrado exitosamente en el ' . env('APP_NAME'))
            ->line('Por favor activa tu cuenta presionando el botón: ')
            ->action('Activar Cuenta', route('activate.user', $user->activation_code))
            ->line('Gracias por utilizar el ' . env('APP_NAME'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
