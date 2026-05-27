<?php
/**
 * src/Controllers/ExperienceController.php — CRUD de experiencia laboral
 */

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Experience;

class ExperienceController extends Controller
{
    private Experience $model;

    public function __construct()
    {
        $this->model = new Experience();
    }

    /** GET /admin/experiences */
    public function index(): void
    {
        $this->render('experiences/index', [
            'pageTitle'   => 'Experiencia',
            'user'        => Auth::user(),
            'flash'       => $this->getFlash(),
            'experiences' => $this->model->all(),
        ]);
    }

    /** GET /admin/experiences/create */
    public function create(): void
    {
        $this->render('experiences/create', [
            'pageTitle' => 'Nueva Experiencia',
            'user'      => Auth::user(),
            'csrf'      => $this->csrfToken(),
        ]);
    }

    /** POST /admin/experiences/create */
    public function store(): void
    {
        $this->verifyCsrf();

        if (empty($_POST['company']) || empty($_POST['role']) || empty($_POST['start_date'])) {
            $this->flash('error', 'Empresa, rol y fecha de inicio son obligatorios.');
            $this->redirect('experiences/create');
        }

        $this->model->create($_POST);

        $this->flash('success', 'Experiencia creada correctamente.');
        $this->redirect('experiences');
    }

    /** GET /admin/experiences/edit/{id} */
    public function edit(string $id): void
    {
        $experience = $this->model->find((int) $id);

        if (!$experience) {
            $this->abort(404, 'Experiencia no encontrada.');
        }

        $this->render('experiences/edit', [
            'pageTitle'  => 'Editar Experiencia',
            'user'       => Auth::user(),
            'csrf'       => $this->csrfToken(),
            'experience' => $experience,
        ]);
    }

    /** POST /admin/experiences/edit/{id} */
    public function update(string $id): void
    {
        $this->verifyCsrf();

        $experience = $this->model->find((int) $id);
        if (!$experience) {
            $this->abort(404, 'Experiencia no encontrada.');
        }

        if (empty($_POST['company']) || empty($_POST['role']) || empty($_POST['start_date'])) {
            $this->flash('error', 'Empresa, rol y fecha de inicio son obligatorios.');
            $this->redirect("experiences/edit/{$id}");
        }

        $this->model->update((int) $id, $_POST);

        $this->flash('success', 'Experiencia actualizada correctamente.');
        $this->redirect('experiences');
    }

    /** POST /admin/experiences/delete/{id} */
    public function destroy(string $id): void
    {
        $this->verifyCsrf();

        $experience = $this->model->find((int) $id);
        if (!$experience) {
            $this->abort(404, 'Experiencia no encontrada.');
        }

        $this->model->delete((int) $id);

        $this->flash('success', "Experiencia en \"{$experience['company']}\" eliminada.");
        $this->redirect('experiences');
    }
}
