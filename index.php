<?php
      include_once 'header.php';
?>
        <section class="main-container">
            <div class="main-wrapper">
                <h2>Homepage</h2>
				Welcome to this Super Secure PHP Application.
				
				<?php
				//DATABASE SETUP
					echo "<br>";
					$conn = mysqli_connect("localhost","TEST","","secureappdev");
					
					//If can't connect, create database and refresh
					if(! $conn ) {
						echo "<br><h2>";
						echo "Refresh this page to complete setup.";
						echo "</h2><br>";

						$connB = mysqli_connect("localhost","TEST","");
						
						mysqli_query($connB,"CREATE DATABASE secureappdev");

						//Make users table to hold user information
						$connC = mysqli_connect("localhost","TEST","","secureappdev");
						$makeUsers = "CREATE TABLE `sapusers` 
						(
							`user_id` int(11) NOT NULL AUTO_INCREMENT,
							user_uid varchar(256) NOT NULL,
							user_pwd varchar(256) NOT NULL,
							user_admin int(2) NOT NULL DEFAULT 0,
							primary key (`user_id`)
						)";
						mysqli_query($connC,$makeUsers);
						
						//Add admin user with default password
						$makeAdmin = "INSERT INTO `sapusers` (`user_id`, `user_uid`, `user_pwd`, `user_admin`) VALUES (0, 'admin', 'AdminPass1!', '1')";
						mysqli_query($connC,$makeAdmin);

						//Make table to track pre-auth sessions that should be blocked for failed login attempts
						$makeCounter = "CREATE TABLE `failedLogins`
						(
							`event_id` int(11) NOT NULL AUTO_INCREMENT,
							`ip` varchar(128) NOT NULL,
							`timeStamp` datetime NOT NULL,
							`failedLoginCount` int(11) NOT NULL,
							`lockOutCount` int(11) NOT NULL,
							primary key (`event_id`)
						)";
						mysqli_query($connC,$makeCounter);

						//Actual table for sequential login events to be viewed by admin once authenticated
						$makeCounter = "CREATE TABLE `loginEvents`
						(
							`event_id` int(11) NOT NULL AUTO_INCREMENT,
							`ip` varchar(128) NOT NULL,
							`timeStamp` datetime NOT NULL,
							`user_id` varchar(50) NOT NULL,
							`outcome` varchar(7) NOT NULL,
							primary key (`event_id`)
						)";
						mysqli_query($connC,$makeCounter);
					 }
					 
					//Message if login fails 
					echo "<br>";
					if (isset($_SESSION['failedMsg']))
					{
						echo $_SESSION['failedMsg'];
						unset($_SESSION['failedMsg']);
					}

					//Message if locked out 
					if(isset($_SESSION['lockedOut'])) {
						echo $_SESSION['lockedOut'];
						unset($_SESSION['lockedOut']);
					}

					//Remaining seconds for current lockout
					if(isset($_SESSION['timeLeft'])) {
						echo " (" . $_SESSION['timeLeft'] . " seconds remaining).";
						unset($_SESSION['timeLeft']);
					}

					//Print messages re: registration
					if(isset($_SESSION['register'])) {
						echo $_SESSION['register'];
						unset($_SESSION['register']);
					}

					//Print messages re: changing password
					if(isset($_SESSION['resetError'])) {
						echo $_SESSION['resetError'];
						unset($_SESSION['resetError']);
					}
                ?>
				
            </div>
        </section>

<?php
	include_once 'footer.php';
?>