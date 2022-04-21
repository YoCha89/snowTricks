Project Name : SnowTricks <br/>

The App, SnowTricks, works both as a directory of snowboard tricks and a chat room for snowboard enthusiast. The site allows:<br/>

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
    <li>To make the mailer work, register for free in MailTrap. An app that will capture our mail but work like a mailbox, allowing the test of mailer related action within the app </li>
    <li>Two function requires a working email system :</li>
        <ol>             
            <li>
                 1. verifyEmail : which allow the confirmation of a register and the assurance the mail given is an effective one<hr/>
                 In  src>controller>RegistrationController, you'll find the protected function verifyEmail(). Put the mail adress you will use to send email in the following code line : ->from(new Address('YOUR_ADRESS', 'systemMail'))
            </li>       
            <li>
                 2. newPassEmail : to use the user access to his own email as a safety check to update the app password.<hr/>
                 In  src>controller>SecurityController, you'll find the protected function newPassEmail. Put the mail adress you will use to send email in the following code line : ->from(new Address('YOUR_ADRESS', 'systemMail'))
            </li>  
        </ol>
    <li>You can create a user account through the App interface.</li>
    <li>To check the app functionnement with admin right, connect with the test admin account :</li>
        ◦ Pseudo : admin@gmail.com
        ◦ Pass : 1234
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
