<?php
/**
 * src/Controllers/ContactController.php — Gestión de mensajes de contacto
 */

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Contact;

class ContactController extends Controller
{
    private Contact $model;

    public function __construct()
    {
        $this->model = new Contact();
    }

    /** GET /admin/contacts — Lista todos los mensajes */
    public function index(): void
    {
        $this->render('contacts/index', [
            'pageTitle' => 'Mensajes de Contacto',
            'user'      => Auth::user(),
            'flash'     => $this->getFlash(),
            'contacts'  => $this->model->all(),
            'unread'    => $this->model->countUnread(),
        ]);
    }

    /** POST /admin/contacts/read/{id} — Marca un mensaje como leído */
    public function markAsRead(string $id): void
    {
        $this->verifyCsrf();

        $contact = $this->model->find((int) $id);
        if (!$contact) {
            $this->abort(404, 'Mensaje no encontrado.');
        }

        $this->model->markAsRead((int) $id);

        $this->redirect('contacts');
    }

    /** POST /admin/contacts/delete/{id} — Elimina un mensaje */
    public function destroy(string $id): void
    {
        $this->verifyCsrf();

        $contact = $this->model->find((int) $id);
        if (!$contact) {
            $this->abort(404, 'Mensaje no encontrado.');
        }

        $this->model->delete((int) $id);

        $this->flash('success', "Mensaje de \"{$contact['name']}\" eliminado.");
        $this->redirect('contacts');
    }
}
