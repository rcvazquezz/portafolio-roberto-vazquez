<?php
/**
 * src/Controllers/EducationController.php — CRUD de formación académica
 */

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Education;

class EducationController extends Controller
{
    private Education $model;

    public function __construct()
    {
        $this->model = new Education();
    }

    /** GET /admin/education */
    public function index(): void
    {
        $this->render('education/index', [
            'pageTitle' => 'Educación',
            'user'      => Auth::user(),
            'flash'     => $this->getFlash(),
            'education' => $this->model->all(),
        ]);
    }

    /** GET /admin/education/create */
    public function create(): void
    {
        $this->render('education/create', [
            'pageTitle' => 'Nueva Educación',
            'user'      => Auth::user(),
            'csrf'      => $this->csrfToken(),
        ]);
    }

    /** POST /admin/education/create */
    public function store(): void
    {
        $this->verifyCsrf();

        if (empty($_POST['institution']) || empty($_POST['degree']) || empty($_POST['start_year'])) {
            $this->flash('error', 'Institución, título y año de inicio son obligatorios.');
            $this->redirect('education/create');
        }

        $this->model->create($_POST);

        $this->flash('success', 'Educación creada correctamente.');
        $this->redirect('education');
    }

    /** GET /admin/education/edit/{id} */
    public function edit(string $id): void
    {
        $item = $this->model->find((int) $id);

        if (!$item) {
            $this->abort(404, 'Registro de educación no encontrado.');
        }

        $this->render('education/edit', [
            'pageTitle' => 'Editar Educación',
            'user'      => Auth::user(),
            'csrf'      => $this->csrfToken(),
            'item'      => $item,
        ]);
    }

    /** POST /admin/education/edit/{id} */
    public function update(string $id): void
    {
        $this->verifyCsrf();

        $item = $this->model->find((int) $id);
        if (!$item) {
            $this->abort(404, 'Registro de educación no encontrado.');
        }

        if (empty($_POST['institution']) || empty($_POST['degree']) || empty($_POST['start_year'])) {
            $this->flash('error', 'Institución, título y año de inicio son obligatorios.');
            $this->redirect("education/edit/{$id}");
        }

        $this->model->update((int) $id, $_POST);

        $this->flash('success', 'Educación actualizada correctamente.');
        $this->redirect('education');
    }

    /** POST /admin/education/delete/{id} */
    public function destroy(string $id): void
    {
        $this->verifyCsrf();

        $item = $this->model->find((int) $id);
        if (!$item) {
            $this->abort(404, 'Registro de educación no encontrado.');
        }

        $this->model->delete((int) $id);

        $this->flash('success', "\"{$item['degree']}\" eliminado.");
        $this->redirect('education');
    }
}
