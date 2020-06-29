<?php

/**
 * Plugin Name
 *
 * @package     PluginPackage
 * @author      Your Name
 * @copyright   2019 Your Name or Company Name
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: soumayasf
 * Plugin URI:  https://soumayabara/Custom_Form/Plugins/Custom_Form
 * Description: Custom Form Contact Plugin That can Help You Create simple Form Contact.
 * Version:     1.0.0
 * Author:      soumaya bara
 * Author URI:  https://soumayabara/Custom_Form/Plugins
 * Text Domain: plugin-slug
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */


//  EXIST IF ACCESS DIRECTLY
defined('ABSPATH') or die('No script kiddies please!');


// DATABASE REQUIREMENTS
require_once(ABSPATH . 'wp-config.php');
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($connection, DB_NAME);


// checking if there is table 
function checkIfThereIsTable()
{
    global $connection;
    $sql = "SELECT * FROM wp_contact";
    $result = mysqli_query($connection, $sql);
    return $result;
}

if (checkIfThereIsTable() == false) {
    global $connection;
    $sql = "CREATE TABLE wp_contact (idContact int NOT NULL PRIMARY KEY AUTO_INCREMENT, FirstName varchar(25) NOT NULL, LastName varchar(25) NOT NULL, Email varchar(50) NOT NULL, Subjects varchar(255) NOT NULL, Content varchar(255) NOT NULL)";
    $result = mysqli_query($connection, $sql);
    return $result;
}



function CustomFormCore($atts)
{
    // shortcode_atts( array $pairs, array $atts, string $shortcode = '' )
    
    // Description #

    // The pairs should be considered to be all of the attributes which are supported by the caller and given as a list. The returned attributes will only contain the attributes in the $pairs list.
    // If the $atts list has unsupported attributes, then they will be ignored and removed from the final returned list.
    extract(shortcode_atts(array(
        'Fname' => 'true',
        'Lname' => 'true',
        'mail' => 'true',
        'subject' => 'true',
        'content' => 'true'
    ), $atts));

    if ($Fname == 'true') {
        $Fnames = '<label for="firstname">FirstName</label><input type="text" class="form-control" placeholder="First name" id="firstname" name="firstname">';
    } else {
        $Fnames = '';
    }
    // **********
    if ($Lname == 'true') {
        $Lnames = '<label for="lastname">LastName</label><input type="text" class="form-control" placeholder="Last name" id="lastname" name="lastname"> ';
    } else {
        $Lnames = '';
    }
    // ***********
    if ($mail == 'true') {
        $mails = ' <label for="email">Email address</label> <input type="email" id="email" name="email" placeholder="Please enter your email " class="form-control" id="email" aria-describedby="emailHelp"> <small id="emailHelp" class="form-text text-muted">We\'ll never share your email with anyone else.</small> ';
    } else {
        $mails = '';
    }
    // **********
    if ($subject == 'true') {
        $subjects = '<label for="subject"> Subject </label>  <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject "> ';
    } else {
        $subjects = '';
    }
    // *********
    if ($content == 'true') {
        $contents = ' <div class="input-group-prepend">    <span class="input-group-text">Content</span>  </div>  <textarea name="content" class="form-control" aria-label="With textarea"></textarea>';
    } else {
        $contents = '';
    }

    $content = "<h4> Contact Form</h5>";
    $content .= ' <form action="" method="post">';
    $content .= ' <div class="form-group"> ' . $Fnames . '</div>';
    $content .= '<div class="input-group"> ' . $Lnames . '</div>';
    $content .= ' <div class="form-group">' . $mails . ' </div>';
    $content .= ' <div class="form-group"> ' . $subjects . ' </div>';
    $content .= ' <div class="input-group"> ' . $contents . ' </div>';
    $content .= ' <hr>';
    $content .= ' <button type="submit" class="btn btn-outline-primary" name="SendInfos">SEND</button>';

    $content .= ' </form>';

    if (isset($_POST['SendInfos'])) {
        // Declaring the variables 
        $Fname = $_POST['firstname'];
        $Lname = $_POST['lastname'];
        $mail = $_POST['email'];
        $subjct = $_POST['subject'];
        $text = $_POST['content'];

        // checking the validity

        if (empty($Fname)) {
            echo 'Firstname Required';
        } elseif (empty($Lname)) {
            echo 'Lastname Required';
        } elseif (empty($mail)) {
            echo 'Email Required';
        } elseif (empty($subjct)) {
            echo 'Subject Required';
        } elseif (empty($text)) {
            echo 'Content Required';
        } else {

            // inserting the data into the database
            global $connection;
            $sql = "INSERT INTO wp_contact (FirstName, LastName, Email, Subjects, Content) VALUES ('$Fname', '$Lname', '$mail', '$subjct', '$text')";
            $result = mysqli_query($connection, $sql);
            // return $result;

        }
    }



    return $content;
}

// add_shortcode( string $tag, callable $callback )

// DESCRIPTION #

// Care should be taken through prefixing or other means to ensure that the shortcode tag being added is unique and will not conflict with other, already-added shortcode tags. In the event of a duplicated tag, the tag loaded last will take precedence.
add_shortcode('SCF', 'CustomFormCore');


//add_action( string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1 )

// Description #

//Actions are the hooks that the WordPress core launches at specific points during execution, or when specific events occur. Plugins can specify that one or more of its PHP functions are executed at these points, using the Action API.
add_action("admin_menu", "add_admin_menu");


function add_admin_menu(){   
    //add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
   
    //Description #

    //This function takes a capability which will be used to determine whether or not a page is included in the menu.
    // The function which is hooked in to handle the output of the page must check that the user has the required capability as well.
     add_menu_page("soumayasfscf", "SCF", 4, "SCF", "Guide");
    }

function Guide()
{
    $tutorial = '<div class="main-page" style="margin: auto;"><h1 style="text-align: center;">HOW TO USE THIS PLUGIN?</h1></div>';
    $tutorial .= '<p> it\'s really quiet simple to use this plugin okay.</p>';
    $tutorial .= '<p> First thing to do is to open the page that you wan to use it as a contact form.or inside a post whatever you want</p>';
    $tutorial .= '<p> then select a shortcode to add .</p>';
    print $tutorial;
}