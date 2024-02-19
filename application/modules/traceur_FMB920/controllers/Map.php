<?php 

	/**
	 * 
	 */
	class Map extends CI_Controller
	{
		
		function __construct()
		{
			// code...
			parent::__construct();
		}


		function index(){

			$fontinfo = $this->input->post('rtoggle');

			$info = '';

			if($fontinfo == ''){

				$info = 'streets';

			}else{

				$info = $fontinfo;
			}

			$data['info'] = $info;

			$this->load->view('Map_view',$data);
		}


		function getmap(){

		$get_data = $this->Model->getRequeteOne('SELECT * FROM `tracking_data` WHERE `id` = (SELECT MAX(`id`) FROM tracking_data);');

		$data = '{"name":"iss","id":25544,"latitude":'.$get_data['latitude'].',"longitude":'.$get_data['longitude'].',"altitude":427.6731067247,"velocity":27556.638607061,"visibility":"eclipsed","footprint":4546.2965721564,"timestamp":1690338162,"daynum":2460151.5990972,"solar_lat":19.512848632241,"solar_lon":145.96751425687,"units":"kilometers"}';

		 

      	echo $data;
		}


		function trajet(){


			$get_data = $this->Model->getRequete('SELECT * FROM `tracking_data`');
			$vit_moy = $this->Model->getRequeteOne('SELECT AVG(`vitesse`) moy_vitesse FROM `tracking_data` WHERE 1');
			$date_debfin = $this->Model->getRequeteOne('SELECT MIN(`date`) datemin,MAX(`date`) datemax FROM `tracking_data` WHERE 1');

			

			$track = '';

			foreach ($get_data as $key) {
				
				$track.='['.$key['longitude'].','.$key['latitude'].'],';
			}

			$track.='@';

			$track = str_replace(',@', "", $track);

			$data['track'] = $track;
			$data['vit_moy'] = $vit_moy;
			$data['date_debfin'] = $date_debfin;

			 

			$this->load->view('Maptracking_view',$data);
		}
	}

 ?>