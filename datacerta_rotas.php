<?php
/**
 * Fatura Demillus Base
 * -------------------------------------------------------------------------------------------
 * @author
 * @version 1.0
 * @copyright 2015 Data Certa
 */
// seta variavel


// pega a configuracao
require_once("inc/config.inc");
// seta o link atual
$selfLink = HOST.$PHP_SELF;
// pega o header
$_Exec  = HOST."/Exec/datacerta_rotas_ajax.php";
require_once("inc/header.inc");
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Waypoints in directions</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 600px;
        width: 1000px;
      }
#right-panel {
  font-family: 'Roboto','sans-serif';
  line-height: 30px;
  padding-left: 10px;
}

#right-panel select, #right-panel input {
  font-size: 15px;
}

#right-panel select {
  width: 100%;
}

#right-panel i {
  font-size: 12px;
}

      #right-panel {
        margin: 20px;
        border-width: 2px;
        width: 20%;
        float: left;
        text-align: left;
        padding-top: 20px;
      }
      #directions-panel {
        margin-top: 20px;
        background-color: #FFEE77;
        padding: 10px;
      }
    </style>
  </head>
  <body>
    <form action="#" method="POST">
      <input type="text" name="lista" placeholder="LISTA" />
      <input type="submit" name="enviar">
    </form>
    <button class="botao-gravar">Gravar</button>
    <br>
    <div id="map"></div>
    <br>

    <div id="directions-panel"></div>

<?php 
$qry = new consulta($con);
$qry->executa("SELECT e.numlista, dr.id_revend, e.numnotafiscal, dr.nome_revend, dr.latitude, dr.longitude FROM tbentrega e  LEFT JOIN tb_demillus_revend dr ON CAST(e.numconta as integer) = dr.id_revend WHERE numlista = '".$_POST['lista']."' LIMIT 23 ");
?>
    <script>
function initMap() {
  var directionsService = new google.maps.DirectionsService;
  var directionsDisplay = new google.maps.DirectionsRenderer;
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 6,
    center: {lat: 41.85, lng: -87.65}
  });
  directionsDisplay.setMap(map);
  calculateAndDisplayRoute(directionsService, directionsDisplay);

}

var reorder = [];

function calculateAndDisplayRoute(directionsService, directionsDisplay) {
  var waypts = [];
  var checkboxArray = document.getElementById('waypoints');
  order = null;
  dados = [];

  <?php
  for($i=0;$i<$qry->nrw;$i++){
    $qry->navega($i);
    $dados[] = $qry->data['id_revend'];
  ?>
    waypts.push({location: <?php echo "'".$qry->data['latitude'].", ".$qry->data['longitude']."'"; ?>});
  <?php
  }
  ?>
  dados = <?php $dados = json_encode($dados); echo $dados; ?>

  directionsService.route({
    origin: new google.maps.LatLng(-23.600848, -46.592559),
    destination: new google.maps.LatLng(-23.600848, -46.592559),
    waypoints: waypts,
    optimizeWaypoints: true,
    travelMode: google.maps.TravelMode.DRIVING
  }, function(response, status) {
    if (status === google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
      var route = response.routes[0];
      var summaryPanel = document.getElementById('directions-panel');

      summaryPanel.innerHTML = '';
      // For each route, display summary information.
      for (var i = 0; i < route.legs.length; i++) {
        var routeSegment = i + 1;
        summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment +
            '</b><br>';
        summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
        summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
        summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
        summaryPanel.innerHTML += route.legs[i].start_location.lat() + ','+route.legs[i].start_location.lng()+ ' to ';
        summaryPanel.innerHTML += route.legs[i].end_location.lat() + ','+route.legs[i].end_location.lng();
        
      }
      for (var i = 0; i < route.waypoint_order.length; i++) {
        reorder.push(dados[route.waypoint_order[i]]);
      }
      console.log("PRE");
      console.log(dados);
      console.log("REORDER");
      console.log(reorder);

    } else {
      window.alert('Directions request failed due to ' + status);
    }
  });
}

( function( $ ) {
    $(function() {
        $('.botao-gravar').on('click',function(){
            $.ajax({
                method: "POST",
                url: "<?php echo $_Exec; ?>",
                dataType:"json",
                data: { 
                    dados: reorder,
                    numlista: <?php echo $_POST['lista']; ?>
                }
            })
            .done(function( obj ) {
               alert('salvo com sucesso');
            });
        });
    });
} )( jQuery );
    </script>


    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1-Uw_4nTVytmn6n1TLVzoF7nROUVwY4c
&callback=initMap"
        async defer></script>
  </body>
</html>