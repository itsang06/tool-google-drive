<?php
  include 'up.php';
  $ids = array();

  if(isset($_FILES['images']['tmp_name']))
    {
        // Number of uploaded files
        $num_files = count($_FILES['images']['tmp_name']);
        /** loop through the array of files ***/
        for($i=0; $i < $num_files;$i++)
        {
            // check if there is a file in the array
            if(!is_uploaded_file($_FILES['images']['tmp_name'][$i]))
            {
                //$messages[] = 'No file uploaded';
            }
            else
            {
            	$drive = new GoogleDrive();	  
				  	  
				  	$filePath = $_FILES['images']['tmp_name'][$i];
				  	$file_name = $_FILES['images']['name'][$i];
				  	move_uploaded_file($filePath, 'files/' . $file_name);
                    $fileid = $drive->upload('files/', $file_name);
				  	array_push($ids, $fileid);
            }
        }
    }
?>
<html>
	<head>
		<meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" /> 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <style type="text/css">
            #uploadstatus{display: none;}
            .border-upload{padding: 20px;border: 1px solid #eee;}
            .result-row{border-bottom: 1px solid #eee;}
        </style>
	</head>
   <body>  
        <div class="container" style="width: 600px;margin: 0 auto;margin-top: 50px;">
            <div class="col-md-12 col-sm-6 col-xs-12" style="margin-bottom:20px;text-align: center;">
                <img src="logo_teeaether_150.png">
            </div>
            <div class="col-md-12 col-sm-6 col-xs-12" style="margin-bottom: 50px;">
                <div id="uploadstatus" style="text-align: center;">
                    <img src="LoaderIcon.gif">
                </div>
                <div id="result">
                    <?php
                        foreach ($ids as $id){
                            echo '<div class="form-group row result-row">
                            <div class="form-group col-md-6">
                                <img src="https://drive.google.com/thumbnail?id='.$id.'">
                            </div>
                            <div class="form-group col-md-6">                                
                                <textarea name="textarea" rows="5" cols="50" style="width: 100%;">https://drive.google.com/file/d/'.$id.'</textarea>
                            </div>
                        </div>';
                        }                        
                    ?>
                </div>
            </div>
            <form action="" id="uploadForm" method="POST" enctype="multipart/form-data">             			
    			<div class="col-md-12 col-sm-6 col-xs-12 border-upload">
                    <div class="form-group row">                      
                        <div class="col-md-4">
                            <label>File PSD</label>
                        </div>
                        <div class="col-md-8">
                        	<input type="file" name="images[]" accept=".psd" required="required" />
                        </div>
                    </div>    
                    <div class="form-group row">                     
                        <div class="col-md-4">
                            <label>File PNG</label>
                        </div>
                        <div class="col-md-8">
                        	<input type="file" name="images[]" accept=".png" required="required" />
                        </div>
                    </div> 
                    <div class="form-group row">                     
                        <div class="col-md-4">
                            <label>File Banner</label>
                        </div>
                        <div class="col-md-8">
                            <input type="file" name="images[]" accept=".jpg,.png" required="required"/>
                        </div>
                    </div> 
                    <div class="form-group row">                     
                        <div class="col-md-4">
                            <label>File Banner Retarget</label>
                        </div>
                        <div class="col-md-8">
                            <input type="file" name="images[]" accept=".jpg,.png"/>
                        </div>
                    </div> 
                    <div class="form-group row">                     
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-8">
                            <input class="btn btn-primary" type="submit" value="Upload all files"/>
                        </div>
                    </div>                    								
    			</div>    		         
            </form>
      </div>
      <script type="text/javascript">
        function myCopy(linkid) {
          /* Get the text field */
          var copyText = document.getElementById(linkid);
          alert(copyText);
          copyText.select();
          document.execCommand("copy");
        }
          $(document).ready(function() { 
            $('#uploadForm').submit(function(e) {   
                $('#uploadstatus').show();
            });
        });
      </script>
   </body>
</html>