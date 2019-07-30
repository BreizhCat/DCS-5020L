<?php
require_once 'config.php';
require_once 'Ptz.php';

$ptz = new Ptz(CAM_HOST, CAM_USERNAME, CAM_PASSWORD);

$stepSize = Ptz::DEFAULT_STEPSIZE;
if (isset($_GET['stepsize']) && intval($_GET['stepsize']) > 0) {
    $stepSize = intval($_GET['stepsize']);
}
if (isset($_GET['command'])) {
    switch ($_GET['command']) {
        case Ptz::POSITION_COMMAND:
            if (!isset($_GET[Ptz::XPOS], $_GET[Ptz::YPOS])) {
                throw new InvalidArgumentException('Missing x/y pos');
            }
            $ptz->setPosition($_GET[Ptz::XPOS], $_GET[Ptz::YPOS]);
            break;
        case Ptz::PRESET_COMMAND:
            $ptz->setPreset($_GET[Ptz::PRESET_ID]);
            break;
        case Ptz::PATROL_COMMAND:
        case Ptz::STOP_COMMAND:
            $ptz->setPatrol($_GET['command']);
            break;
        default:
            throw new InvalidArgumentException('Invalid command');
    }
   // die;
}
?>
<!doctype html>
<html lang="fr">
	<head>
		<link rel="icon" type="image/png" href="https://eu.dlink.com/_include/redesign/images/favicon.ico">
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DLink Monitor</title>
	    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	    <!-- Bootstrap CSS -->
	    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/darkly/bootstrap.min.css" rel="stylesheet" integrity="sha384-w+8Gqjk9Cuo6XH9HKHG5t5I1VR4YBNdPt/29vwgfZR485eoEJZ8rJRbm3TR32P6k" crossorigin="anonymous">
        <script type="text/javascript">
            $(function() {
                // make all links ajax calls ;
                $('.ajax a').each(function() {
                    jQuery(this).click( function(e) {
                        $.ajax(this.href);
                        e.preventDefault();
                       
                    })
                })
            })
			
			var oTimer = setInterval(refreshPicture, 15000);
			
			function refreshPicture() {
				jQuery(".view").attr("src", "image.php?"+Math.random());
			}
        </script>
    </head>
    <body>
    	<div id="content" class="container">
    		<div class="card border-primary mb-3 m-5">
				<div class="card-header text-center">Gestion Caméra DCS-5020L</div>
				<img class="view mx-auto" id="view" src="image.php" alt="Image Overview" />
				<div class="card-body">
					<h4 class="card-title mx-auto">Déplacement</h4>
    				<p class="card-text">
    					<div class="btn-group mx-auto" role="group">
	                		<?php
	                			$urls = $ptz->getPositions($stepSize);
	                			foreach ($urls as $name => $url) 
	                    			printf('<a class="btn btn-primary" href="%s" alt="%s">%s</a>', $url, $name, $name);
	                		?>	
	            		</div>
    				</p>
				</div>
				<div class="card-footer text-center">
					<h4 class="card-title">Pas</h4>
					<form action="" method="get" class="form-inline">
	                	<div class="form-group row mx-auto">
	                		<input class="form-control" type="text" name="stepsize" size="2" maxlength="3" value="<?php echo $stepSize; ?>" />
	                    	<input class="btn btn-info" type="submit" name="set" value="Envoyer" />
	            		 </div>
            		</form>
				</div>
			</div>
    	</div>
    </body>
</html>
