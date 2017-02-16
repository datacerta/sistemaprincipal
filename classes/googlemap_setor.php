<?php
/**
 * googlemap.php :: Show map, place markers, info windows.
 *
 * googlemap version 1.0.0.0
 * copyright (c) 2010 by Sandeep Kumar
 * googlemap is an open source PHP class library to create easliy customized google map. 
 * googlemap is released under the terms of the LGPL license
 * http://www.gnu.org/copyleft/lesser.html#SEC3
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 * @package Google Map
 * @copyright Copyright (c) 2010-2020  by Sandeep Kumar
 * @license http://www.gnu.org/copyleft/lesser.html#SEC3 LGPL License
 */

/**
 *googlemap is an open source PHP class library for easily create googlemap, place multiple markers.
 * @package Google Map
 */

class GOOGLE_API_3
{

	var $code='';  // Do not edit this.
	var $zoom=14; // Zoop Level.
	//var $center_lat='28.022900221052016'; // google map center location
	//var $center_lng='73.3011245727539'; // google map center location
	var $divID='map'; // The div id where you want to 	place your google map
	var $marker=array(); // Array to store markers information. 
	var $instance=1;
	function __construct()
	{
		echo '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyA1-Uw_4nTVytmn6n1TLVzoF7nROUVwY4c"></script>';
	}
	// Intialized google map scripts.
	private function start()
	{
		
		$this->code='
		<script type="text/javascript">
      (function() {
        window.onload = function(){
        	// Creating a LatLng object containing the coordinate for the center of the map  
          var latlng = new google.maps.LatLng('.$this->center_lat.', '.$this->center_lng.');  
          // Creating an object literal containing the properties we want to pass to the map  
          var options = {  
          	zoom: '.$this->zoom.',
          	center: latlng,
          	mapTypeId: google.maps.MapTypeId.ROADMAP
          };  
          // Calling the constructor, thereby initializing the map  
          var map = new google.maps.Map(document.getElementById("'.$this->divID.'"), options); ';
		   
          
		  for($i=0;$i<count($this->marker);$i++)
		  {
		  
			 $this->code.=' var marker'.$i.' = new google.maps.Marker({
				position: new google.maps.LatLng('.$this->marker[$i]['lat'].', '.$this->marker[$i]['lng'].'), 
				map: '.$this->marker[$i]['map'].',
				title: "'.$this->marker[$i]['title'].'",
				clickable: '.$this->marker[$i]['click'].',
				icon: "'.$this->marker[$i]['icon'].'"
			  });';
		  
		  // Creating an InfoWindow object
			if($this->marker[$i]['info']!='')
			{
				$this->code.=' var infowindow'.$i.' = new google.maps.InfoWindow({content: "'.$this->marker[$i]['info'].'"}); ';
	   			$this->code.=" google.maps.event.addListener(marker".$i.", 'click', 
	   			function() { 
	   				infowindow".$i.".open(map, marker".$i."); 
	   				$('.gravar').on('click',
	   					function(){
							$.ajax({
								method: 'POST',
								url: 'http://".$_SERVER["HTTP_HOST"]."/Exec/setor_ajax.php',
								dataType:'json',
								data: {
									idrevend: $(this).next().val(),
									setor: $(this).prev().val(),
								}
							})
							.done(function( obj ) {
								if(obj.status == 1){
									location.reload();
								}else{
									alert('Erro ao Gravar');
									return false;
								}
							});
	   					}); 
	   			});"; 
			}
	}
    
	
	$this->code.='	}
      })();
		</script>';
		
	}

	// Add markers to google map.
	
	public function addMarker($lat,$lng,$click,$title,$info,$icon='',$map='map')
	{
		$count=count($this->marker);	
		$this->marker[$count]['lat']=$lat;
		$this->marker[$count]['lng']=$lng;
		$this->marker[$count]['map']=$map;
		$this->marker[$count]['title']=$title;
		$this->marker[$count]['click']=$click;
		$this->marker[$count]['icon']=$icon;
		$this->marker[$count]['info']=$info;
	}
	
	// Call this function to create a google map.
	
	public function showmap()
	{
		$this->start();
		$this->instance++;
		return $this->code;
	}
}


?>
