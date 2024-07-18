<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactanosMaileable extends Mailable
{
    use Queueable, SerializesModels;

    public $nombre;
    public $hora;
    public $fecha;
    public $nombreRuta;
    public $mensaje;

    /**
     * Create a new message instance.
     */
    public function __construct($nombre, $hora, $fecha, $nombreRuta, $mensaje)
    {
        $this->nombre = $nombre;
        $this->hora = $hora;
        $this->fecha = $fecha;
        $this->nombreRuta = $nombreRuta;
        $this->mensaje = $mensaje;
    }

    public function build()
    {
        return $this->view('emails.contactanos')
            ->with([
                'nombre' => $this->nombre,
                'hora' => $this->hora,
                'fecha' => $this->fecha,
                'nombreRuta' => $this->nombreRuta,
                'mensaje' => $this->mensaje,
            ])
            ->from('fgrcalifa@gmail.com', 'Francisco')
            ->subject('Contactanos Maileable');
    }
}
