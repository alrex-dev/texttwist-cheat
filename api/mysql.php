<?php
class Database {
    var $host = 'localhost'; //MySQL host
    var $user = ''; //MySQL username
    var $pass = ''; //MySQL user password
    var $db = ''; //MySQL database
    var $conn = null;
    
    function __construct() {
        if ( function_exists( 'mysqli_connect' ) ) {
			if ($this->conn = @mysqli_connect( $this->host, $this->user, $this->pass )) {
                if ($this->db != '' && !mysqli_select_db( $this->conn, $this->db )) {
                    echo 'Error when selecting database!';
                }
                
                mysqli_set_charset( $this->conn, 'utf8' );
            } else {
                echo 'Cannot connect to database!';
            }
		} else {
            echo 'mysqli extension not available!';
        }
    }
    
    function escapeString( $string ) {
		if ( version_compare( phpversion(), '4.3.0', '<' ) ) {
			$string = mysqli_escape_string( $string );
		} else 	{
			$string = mysqli_real_escape_string( $this->conn, $string );
		}
		
		return $string;
	}
    
    function execute($sql) {
        $resource = mysqli_query( $this->conn, $sql );
        
        return $resource;
    }
    
    function getResults($sql) {
        $results = $this->execute($sql);
        $data = array();
        
        if ($results) {
            while ( $row = mysqli_fetch_object( $results ) ) {
                $data[] = $row;
            }
            
            mysqli_free_result( $results );
        }
        
        return $data;
    }
}
?>