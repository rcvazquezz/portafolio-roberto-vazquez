<?php
/**
 * src/Controllers/DashboardController.php — Página principal del panel
 */

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Project;
use Models\Experience;
use Models\Education;
use Models\Contact;

class DashboardController extends Controller
{
    /**
     * GET /admin/ o /admin/dashboard — Muestra métricas rápidas del portafolio.
     */
    public function index(): void
    {
        $projects    = new Project();
        $experiences = new Experience();
        $education   = new Education();
        $contacts    = new Contact();

        $this->render('dashboard/index', [
            'pageTitle'      => 'Dashboard',
            'user'           => Auth::user(),
            'flash'          => $this->getFlash(),
            'totalProjects'  => count($projects->all()),
            'totalExp'       => count($experiences->all()),
            'totalEdu'       => count($education->all()),
            'unreadContacts' => $contacts->countUnread(),
            'totalContacts'  => count($contacts->all()),
            'recentContacts' => array_slice($contacts->all(), 0, 5),
        ]);
    }
}
