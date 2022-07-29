<?php
   include('resize_class.php');
   $image = new SimpleImage();
//   $image->load();
   
   $imagename_tosave='uniquename.jpg';

    if (!is_dir('./Images')) {
    mkdir('./Images');
    }

   if (!is_dir('./Images/Thumbnail')) {
    mkdir('./Images/Thumbnail');
    }

    if (!is_dir('./Images/Large')) {
    mkdir('./Images/Large');
    }

    if (!is_dir('./Images/Zoom')) {
    mkdir('./Images/Zoom');
    }

/* small/Thumbnail   */
   $image->resize(100,100);
   $image->save('./Images/Thumbnail/'.$imagename_tosave);

/* Large */
   $image->resize(620,620);
   $image->save('./Images/Large/'.$imagename_tosave);

/* Zoom */
   $image->resize(500,500);
   $image->save('./Images/Zoom/'.$imagename_tosave);

?>