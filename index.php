<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check in</title>
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style>
        .position-absolute {
            top: 20vh;
            left:
                70vh;
        }
    </style>
</head>

<body>
    <?php include_once('validation.php'); ?>
    <?php  include_once('addUser.php');   ?>
    <div class="container">
    <h1 class="display-1 text-center mb-2">SmartTech 2 Project</h1> <hr>
        <h2 class="pb-2">Check in system</h2>
        <?php if ($error == true) : ?>
                    <div class="card  bg-danger text-white shadow ">
                        <div class="card-title d-flex justify-content-between">
                            <h1>Error indringer!!</h1>
                            <a href="" class=" text-white"><svg class="bi bi-x mt-2" width="2em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z" />
                                    <path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z" />
                                </svg>
                            </a>
                        </div>
                        <div class="card-body text-center pt-0">
                            <h4>Onjuiste code.</h4>
                        </div>
                    </div>
            <?php endif; ?>
        <!-- Navigation -->
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-gastenlijst-tab" data-toggle="pill" href="#pills-gastenlijst" role="tab" aria-controls="pills-home" aria-selected="true">Gastenlijst</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Errors</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Binnen</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-gastenlijst" role="tabpanel" aria-labelledby="pills-gastenlijst-tab">
                <div class="card">
                    <p id="status" data-status="code-<?php echo $_GET['status']; ?>"></p>
                    <div class="card-body">
                        <?php if(isset($message)){ echo '<p > New user added! </p>'; }  ?>
                        <h2 class="">Nieuwe gast toevoegen:</h2>
                        <form class="form-inline d-flex justify-content-left" method="post" action="" enctype="multipart/form-data">
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="user" class="col-form-label mr-4">Voornam en Achternaam</label>
                                <input type="text" name="newUser" class="form-control" id="user" placeholder="Voornam en Achternaam">
                            </div>
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="code" class="col-form-label mr-4">Code</label>
                                <input type="text" name="newCode" class="form-control" id="code" placeholder="Code">
                            </div>
                            <button type="submit" name="submit" class="btn btn-success mb-2">Voeg toe</button>

                        </form>
                    </div>
                </div>
                <h2 class="mt-3">Gasten Lijst:</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Voornaam en achternaam</th>
                            <th scope="col">Toegangs code</th>
                            <th scope="col">Checked?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < sizeof($users); $i++) : ?>
                            <tr>
                                <th scope="row"><?php echo $i + 1 ?></th>
                                <td> <?php echo $users[$i][1] ?></td>
                                <td><?php echo $users[$i][2] ?></td>
                                <td><?php
                                    if ($users[$i][3]  == 1) {
                                        echo "YES";
                                    } else {
                                        echo "NO";
                                    } ?>
                                </td>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <h2 class="mt-3">Errors:</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Date</th>
                            <th scope="col">Toegangs code</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php for($i =0; $i < sizeof($errors); $i ++): ?>
                        <tr>
                            <th scope="row"><?php echo $i+1; ?></th>
                            <td><?php echo $errors[$i][2]; ?></td>
                            <td><?php echo $errors[$i][1]; ?></td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                <h2 class="mt-3">Checked in:</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Voornaam en achternaam</th>
                            <th scope="col">Datum tijd</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for($i =0; $i < sizeof($usersBinnen); $i ++): ?>
                        <tr>
                            <th scope="row"><?php echo $i+1; ?></th>
                            <td><?php  echo $usersBinnen[$i][1];?></td>
                            <td><?php echo $usersBinnen[$i][4];?></td>
                        </tr>
                            <?php endfor; ?>
                    </tbody>
                </table>
            </div>
           
            <footer> <hr> <p class="text-center">Marlena Broniewicz 2020</p></footer>
        </div>
        <!-- JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>