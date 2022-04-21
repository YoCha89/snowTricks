Project Name : SnowTricks <br/>

The App _ SnowTricks works both as a directory of snowboard tricks and a chat room for snowboard enthusiast. The site allows:<br/>

<ul>
    <li> The creation/modification of tricks, including images, thumbnails and descriptions</li>
    <li> The creation/modification/management of user accounts</li>
    <li> The display and participation of discussions on each trick with a comment/reply system</li>
</ul>

prerequisite :<br/>

<ul>
    <li> PHP : 7.4.19</li>
    <li> Apache : 2.4.47 </li>
    <li> SQL : 5.7.33 </li>
    <li> Developped with Laragon </li>
</ul>

Installing the app :<br/>

<ol>    
    <li>Download the code files in your projects folder</li>
    <li>Throught your CLI, run "composer install" to get the needed dependances</li>
    <li>Create a database named : snowtricks</li>
    <li>Execute the sql file included with the project file </li>
    
    <li>Au sein du code, quelque modification rendront entièrement viable l’application. Vous devez paramétrer les boites mails utilisées au sein des méthodes gérants les envois d’email (une en frontend, une autre en backend). Cette fonctionnalité a été prévu pour une adresse mail google. A partir de la racine du projet :</li>
        <ol> 
            1. Dans  App>Frontend>Modules>Account>AccountController, au sein de la méthode privée sendMail(), entrez
                <ol>
                    <li>l’adresse email que vous utilisez pour envoyer les mails à partir de l’application ligne : 368 et 373 ($mailAdmin->Username et $mailAdmin->setFrom)</li>
                    <li>le mot de passe de cette boite mail ligne : 369 ($mailAdmin->Password)</li>
                    <li>l’adresse email que vous utilisez pour recevoir les mails et répondre en tant qu’ administrateur, ligne : 274 ($AdminMail) et 403 ($masterAdmin)  </li>
                </ol>
            2. Répétez l’opération pour la méthode du même nom coté backend :  App>Backend>Modules>Admin>AdminController
                <ol>
                    <li>l’adresse email que vous utilisez pour envoyer les mails à partir de l’application ligne : 285 et 290  ($mailAdmin→Username et $mailAdmin->setFrom)</li>
                    <li>le mot de passe de cette boite mail ligne : 286 ($mailAdmin->Password)</li>
                    <li>l’adresse email que vous utilisez pour recevoir les mails et répondre en tant qu’ administrateur, ligne : 153 ($masterAdmin)</li>
                </ol>
        </ol>
    <li>Vous pouvez créer un compte d’utilisateur via l’interface de l’application.</li>
    <li>Vous avez en revanche besoin d’un compte administrateur d’entré. Vous pouvez utiliser les identifiants du compte par défaut :</li>
        ◦ Pseudo : masterAdmin
        ◦ Pass : Master&2021
</ol>
© 2022 GitHub, Inc.
Terms
Privacy
Security
Status
Docs
Contact GitHub
Pricing
API
Training
Blog
About
