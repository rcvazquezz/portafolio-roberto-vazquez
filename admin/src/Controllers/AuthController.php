<?php
/**
 * src/Controllers/AuthController.php — Login y logout del panel
 */

namespace Controllers;

use Core\Controller;
use Core\Auth;

class AuthController extends Controller
{
    /**
     * GET /admin/login — Muestra el formulario de acceso.
     * Si ya hay sesión activa, redirige al dashboard.
     */
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('dashboard');
        }

        $this->render('auth/login', [
            'pageTitle' => 'Acceso al Panel',
            'flash'     => $this->getFlash(),
            'csrf'      => $this->csrfToken(),
        ], withLayout: false);
    }

    /**
     * POST /admin/login — Procesa las credenciales del formulario.
     */
    public function login(): void
    {
        $this->verifyCsrf();

        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        /* Validación básica de campos requeridos */
        if (empty($email) || empty($password)) {
            $this->flash('error', 'Email y contraseña son obligatorios.');
            $this->redirect('login');
        }

        if (Auth::attempt($email, $password)) {
            $this->redirect('dashboard');
        }

        /* Credenciales incorrectas: mensaje genérico (no revelar cuál falló) */
        $this->flash('error', 'Credenciales incorrectas. Intenta de nuevo.');
        $this->redirect('login');
    }

    /**
     * GET /admin/logout — Cierra la sesión y redirige al login.
     */
    public function logout(): void
    {
        Auth::logout();
        $this->redirect('login');
    }
}
