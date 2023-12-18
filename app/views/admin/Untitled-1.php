
			$this->datos['proyecto'] = $this->modeloProject->getProyect($id);
			/* print_r($this->datos['proyecto']); */
			$this->vista("Admin/project_stages", $this->datos);
/* 			echo '<div style="text-align: center; margin-top: 70px; font-size: 20px; position: absolute; top: 5%; left: 58%; transform: translate(-50%, -50%);">';			
 */			echo '<p class="fw-bolder text-center fs-3" style="margin-top: 70px; position: absolute; top: 5%; left: 58%; transform: translate(-50%, -50%);">';			

			/* echo '<strong>'; */
			if ($this->datos['proyecto']->id_category == 1) {
				echo ' Nombre del proyecto: ' .$this->datos['proyecto'] ->folio ;
			} else {
				echo 'Nombre del proyecto: '.$this->datos['proyecto'] ->folio;
			}
			/* echo '</strong>';  */
			echo '<br>';


			echo '</p>';
	       /*  echo '</div>'; */