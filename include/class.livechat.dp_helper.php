<?php 

class DB_Helper {

    private $db;
    private static $db_helper;

    private function __construct() {
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }

    private function __destruct() {
        $this->db->close();
    }

    public static function getHelper() {
        if( self::$db_helper == null ) {
            self::$db_helper = new DB_Helper();
        }
        return  self::$db_helper;
    }

    function writeMessage() {
        if ( $this->db->connect_errno ) {
            echo "Verbindung zur Datenbank konnte nicht hergestellt werden: (" . $this->db->connect_errno . ") " . $this->db->connect_error;
        }

        $chatuser = substr($_POST["chatuser"], 0, 32);
        $message = substr($_POST["message"], 0, 255);
        $useronline = $_POST["useronline"];
        $usersession = session_id();

        $chatuserEscaped = htmlentities(mysqli_real_escape_string($this->db, $chatuser));
        $messageEscaped = htmlentities(mysqli_real_escape_string($this->db, $message));

        $query = "INSERT INTO wp_livechat (chatuser, message, useronline, usersession) VALUES ('$chatuserEscaped', '$messageEscaped', '$useronline', '$usersession')";

        if ( $this->db->real_query($query) ) {
            echo "Nachricht in Datenbank gespeichert";
        } else {
            echo "Ein Fehler ist aufgetreten";
            echo $this->db->errno;
        }
    }

    function readMessages() {
        if ( $this->db->connect_errno ) {
            echo "Verbindung zur Datenbank konnte nicht hergestellt werden: (" . $this->db->connect_errno . ") " . $this->db->connect_error;
        }
        
        $query = "SELECT *
        FROM wp_livechat
        WHERE ID > (SELECT ID FROM wp_livechat WHERE useronline = 0 ORDER BY ID DESC LIMIT 1)";

        /* $query = "SELECT *
            FROM wp_livechat
            WHERE ID > (
                SELECT IF ( 
                (SELECT useronline FROM wp_livechat ORDER BY ID DESC LIMIT 1) = 1,
                    (SELECT ID 
                    FROM wp_livechat
                    WHERE useronline = 0
                    ORDER BY id DESC LIMIT 0, 1)
                ,
                    (SELECT ID 
                    FROM wp_livechat
                    WHERE useronline = 0
                    ORDER BY id DESC LIMIT 1, 1)
                )
            )
            AND ID <= (SELECT ID FROM wp_livechat WHERE useronline = 1 ORDER BY ID DESC LIMIT 1)"; */

        if  ( $this->db->real_query($query) ) {
            $res = $this->db->use_result();
        
            while ( $row = $res->fetch_assoc() ) {
                $chatuser = $row["chatuser"];
                $message = $row["message"];
                $time = date('G:i:s', strtotime($row["time"]));
                
                if( $message != "" ) {
                    if($chatuser == "Visitor" ) {
                        echo "<p class='lv-visitor-message'><strong>$time: </u></strong>$message</p>\n";
                    } else {
                        echo "<p class='lv-employee-message'><strong>$time: </u></strong>$message</p>\n";
                    }
                }
            }
        } else {
            echo "Ein Fehler ist aufgetreten";
            echo $this->db->errno;
        }
    }

    function checkUserOnline() {
        if ($this->db->connect_errno) {
            echo "Verbindung zur Datenbank konnte nicht hergestellt werden: (" . $this->db->connect_errno . ") " . $this->db->connect_error;
        }
        
        $query = "SELECT * FROM wp_livechat ORDER BY time DESC LIMIT 1";

        if ( $this->db->real_query($query) ) {
            $res = $this->db->use_result();
        
            while ( $row = $res->fetch_assoc() ) {
                $useronline = $row["useronline"];
                if( $useronline == 0 ) {
                   echo "false";
                } else {
                   echo "true";
                }
            }
        } else {
            echo "Ein Fehler ist aufgetreten";
            echo $this->db->errno;
        }
    }

    function checkUserSame() {
        if ($this->db->connect_errno) {
            echo "Verbindung zur Datenbank konnte nicht hergestellt werden: (" . $this->db->connect_errno . ") " . $this->db->connect_error;
        }
        
        $query = "SELECT * FROM wp_livechat WHERE chatuser = 'Visitor' ORDER BY time DESC LIMIT 1";

        if ( $this->db->real_query($query) ) {
            $res = $this->db->use_result();
        
            while ( $row = $res->fetch_assoc() ) {
                $useronline = $row["useronline"];
                $usersession = $row["usersession"];
                if( ( $useronline == 1 && $usersession == session_id() ) || $useronline == 0 ) {
                   return true;
                } else {
                    return false;
                }
            }
        } else {
            echo "Ein Fehler ist aufgetreten";
            echo $this->db->errno;
        }
    }

    function clearDatabase() {
        if ( $this->db->connect_errno ) {
            echo "Verbindung zur Datenbank konnte nicht hergestellt werden: (" . $this->db->connect_errno . ") " . $this->db->connect_error;
        }
        
        $query = "DELETE FROM wp_livechat WHERE id > 1";

        if ( $this->db->real_query($query) ) {
            echo "Chatverlauf erfolgreich gelöscht";
        } else {
            echo "Ein Fehler ist aufgetreten";
            echo $this->db->errno;
        }
    }
}