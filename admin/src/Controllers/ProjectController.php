<?php
/**
 * src/Controllers/ProjectController.php — CRUD de proyectos
 */

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Project;

class ProjectController extends Controller
{
    private Project $model;

    public function __construct()
    {
        $this->model = new Project();
    }

    /** GET /admin/projects */
    public function index(): void
    {
        $this->render('projects/index', [
            'pageTitle' => 'Proyectos',
            'user'      => Auth::user(),
            'flash'     => $this->getFlash(),
            'projects'  => $this->model->all(),
        ]);
    }

    /** GET /admin/projects/create */
    public function create(): void
    {
        $this->render('projects/create', [
            'pageTitle' => 'Nuevo Proyecto',
            'user'      => Auth::user(),
            'csrf'      => $this->csrfToken(),
        ]);
    }

    /** POST /admin/projects/create */
    public function store(): void
    {
        $this->verifyCsrf();

        /* Validación de campos requeridos */
        if (empty($_POST['name']) || empty($_POST['description'])) {
            $this->flash('error', 'Nombre y descripción son obligatorios.');
            $this->redirect('projects/create');
        }

        $this->model->create($_POST);

        $this->flash('success', 'Proyecto creado correctamente.');
        $this->redirect('projects');
    }

    /** GET /admin/projects/edit/{id} */
    public function edit(string $id): void
    {
        $project = $this->model->find((int) $id);

        if (!$project) {
            $this->abort(404, 'Proyecto no encontrado.');
        }

        $this->render('projects/edit', [
            'pageTitle' => 'Editar Proyecto',
            'user'      => Auth::user(),
            'csrf'      => $this->csrfToken(),
            'project'   => $project,
        ]);
    }

    /** POST /admin/projects/edit/{id} */
    public function update(string $id): void
    {
        $this->verifyCsrf();

        $project = $this->model->find((int) $id);
        if (!$project) {
            $this->abort(404, 'Proyecto no encontrado.');
        }

        if (empty($_POST['name']) || empty($_POST['description'])) {
            $this->flash('error', 'Nombre y descripción son obligatorios.');
            $this->redirect("projects/edit/{$id}");
        }

        $this->model->update((int) $id, $_POST);

        $this->flash('success', 'Proyecto actualizado correctamente.');
        $this->redirect('projects');
    }

    /** POST /admin/projects/delete/{id} */
    public function destroy(string $id): void
    {
        $this->verifyCsrf();

        $project = $this->model->find((int) $id);
        if (!$project) {
            $this->abort(404, 'Proyecto no encontrado.');
        }

        $this->model->delete((int) $id);

        $this->flash('success', "Proyecto \"{$project['name']}\" eliminado.");
        $this->redirect('projects');
    }
}
