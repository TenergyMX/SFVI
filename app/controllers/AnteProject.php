<?php
	class AnteProject extends Controlador {
		private $modeloProject;
		private $modeloClient;
		private $modeloVisit;
		private $datos = [];

		// Constructor
		function __construct() {
			session_start();
			$this->modeloProject = $this->modelo('Projects');
			$this->userModel = $this->modelo('Users');
			$this->modeloClient = $this->modelo('Clients');
			$this->modeloVisit = $this->modelo('Visits');
			$this->datos['user'] = datos_session_usuario();
			$this->datos['sidebar-item'] = 'proyectos';
		}

		function index() {
			isUserLoggedIn();
			$clientes = $this->userModel->get_customer_users();
			$this->datos['nombre_clientes'] = $clientes->data;
			$this->datos['nombre_estados'] = $this->modeloProject->getStates();
			$this->datos['nombre_proyectos'] = $this->modeloProject->getProjects();
			$this->datos['nombres_visitantes'] = $this->modeloVisit->getVisitantes();
			$this->vista("admin/table_ante_projects", $this->datos);
			print_r($this->datos['nombre_clientes']);
		}

		function stages($id = 0) {
			isUserLoggedIn();
            $this->datos['proyecto'] = $this->modeloProject->getProject($id);
			$id_project = $this->datos['proyecto']->id;
			$id_client = $this->datos['proyecto']->id_client;
			$this->datos['cliente'] = $this->modeloClient->getUser_client($id_client);
			$this->datos['id_type'] = 1;
			$this->datos['stage'][0] = $this->modeloProject->getAnteEtapa1($id_project);
			$this->datos['stage'][1] = $this->modeloProject->getAnteEtapa2($id_project);
			$this->datos['stage'][2] = $this->modeloProject->getAnteEtapa3($id_project);
			$this->datos['stage'][3] = $this->modeloProject->getAnteEtapa4($id_project);
			$this->datos['stage'][4] = $this->modeloProject->getAnteEtapa5($id_project);
			$this->datos['stage'][5] = $this->modeloProject->getAnteEtapa6($id_project);
			$this->datos['stage'][6] = $this->modeloProject->getAnteEtapa7($id_project);

			generate_project_file_path( $this->datos );
            $this->vista("admin/ante_project_stages", $this->datos);

		}

	}
?>