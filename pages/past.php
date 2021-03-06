<?php
session_start();
// VARIABLE POUR MESSAGE D ERREUR
$error = null;
$good = null;
try
{
    // pdo
} 
catch (Exeption $e)
{
    die('erreur :' .$e ->getMessage());
}
include '../vendor/autoload.php';  

$Parsedown = new Parsedown();
$Parsedown->setSafeMode(true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Past Event</title>
    <link href="https://fonts.googleapis.com/css2?family=Itim&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</head>
<body>
<a href="#top" class="btn btn-light position-fixed text-black rounded-circle" style="bottom:10px; right: 10px; z-index:20"> ^ </a>
<?php include 'header.php';




// $today =date("d-m-Y");
// echo "Ajourd'hui : " .$today;?>

<div class="container">
  <?php
$events = $db -> query('SELECT *,
YEAR(date), 
MONTHNAME(date), 
DAY(date), 
DAYNAME(date), 
HOUR(hour), 
MINUTE(hour) 
FROM event 
INNER JOIN category on event.category_id = category.id
where event.date < curdate() or (event.date = curdate() and event.hour < (current_time() + interval 2 HOUR)) 
ORDER BY event.date DESC, event.hour DESC');


while ($elem = $events->fetch()) {
  $commentrow = 0;
  $commentCount = "SELECT comments.event_id FROM comments where event_id = $elem[0]";
  $commenttest= $db->query($commentCount);
  $commentrow = $commenttest->rowCount();

  if($elem['15'] ==0){
    $minToShow = '00';
} else {
    $minToShow = $elem['15'];
}

            echo '<div class="row card mb-3 col-12 flex-row pt-4 pb-4 mx-auto mt-5">';
                    echo '<div class="col-12 col-lg-6 card-body flex-column text-center">';
                        echo '<h2><a class="card-title" href="event.php?id='. $elem['0'] . '">' . $elem['1'] . '</a></h2>';
                        echo '<p class="card-text small text-muted">' . $elem['13'] . ' '. $elem['12'] . ' ' . $elem['11'] . ' ' . $elem['10'] . '   -   ' . $elem['14'] . ':' . $minToShow .'</p>';
                        echo '<p class="card-text small text-muted">' . $elem['title'] . ' | <i class="far fa-comments"></i> : '. $commentrow .'</p>';
                        echo '<div class="card-text overflow-auto mt-3 text-left">' . $Parsedown->text($elem['description']) .'</div>';
                    echo '</div>';
                    echo '<div class="col-12 col-lg-6 align-self-center justify-content-center">';
                        if($elem['image']){
                            echo '<img class="w-100" src="https://mifato.s3.eu-west-3.amazonaws.com/' . $elem['image'] . '" class="img-fluid" alt="event image not found"/>';
                        } else {
                            echo '<img src="https://mifato.s3.eu-west-3.amazonaws.com/no-image.png" class="img-fluid" alt="event image not found"/>';
                        }
                    echo '</div>';
            echo '</div>';
  }?>
</div>
</body>
</html>