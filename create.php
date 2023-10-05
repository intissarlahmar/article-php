<?php
/* Inclure le fichier config */
require_once "config.php";
 
/* Definir les variables */
$name = $prix = $qte = "";
$name_err = $prix_err = $qte_err = "";
 

if($_SERVER["REQUEST_METHOD"] == "POST"){
    /* Validate name */
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Veillez entrez un name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Veillez entrez a valid name.";
    } else{
        $name = $input_name;
    }
    
    /* Validate prix */
   $input_prix = trim($_POST["prix"]);
    if(empty($input_prix)){
        $prix_err = "Veillez entrez le prix.";     
    } elseif(!ctype_digit($input_prix)){
        $prix_err = "Veillez entrez une valeur positive.";
    } else{
        $prix = $input_prix;
    }
    
    /* Validate qte */
    $input_qte = trim($_POST["qte"]);
    if(empty($input_qte)){
        $qte_err = "Veillez entrez l'qte.";     
    } elseif(!ctype_digit($input_qte)){
        $qte_err = "Veillez entrez une valeur positive.";
    } else{
        $qte = $input_qte;
    }
    
    /* verifiez les erreurs avant enregistrement */
    if(empty($name_err) && empty($prix_err) && empty($qte_err)){
        /* Prepare an insert statement */
        $sql = "INSERT INTO article (name, prix, qte) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            /* Bind les variables à la requette preparée */
            mysqli_stmt_bind_param($stmt, "ssd", $param_name, $param_prix, $param_qte);
            
            /* Set parameters */
            $param_name = $name;
            $param_prix = $prix;
            $param_qte = $qte;
            
            /* executer la requette */
            if(mysqli_stmt_execute($stmt)){
                /* opération effectuée, retour */
                header("location: index.php");
                exit();
            } else{
                echo "Oops! une erreur est survenue.";
            }
        }
         
        /* Close statement */
        mysqli_stmt_close($stmt);
    }
    
    /* Close connection */
    mysqli_close($link);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        .wrapper{
            width: 700px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Créer un Article</h2>
                    <p>Remplir le formulaire pour enregistrer un article</p>


                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                           <div class="form-group">
                            <label>Prix</label>
                            <input type="number" name="prix" class="form-control <?php echo (!empty($prix_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $prix; ?>">
                            <span class="invalid-feedback"><?php echo $prix_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Quantite</label>
                            <input type="number" name="qte" class="form-control <?php echo (!empty($qte_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $qte; ?>">
                            <span class="invalid-feedback"><?php echo $qte_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Enregistrer">
                        <a href="index.php" class="btn btn-secondary ml-2">Annuler</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>