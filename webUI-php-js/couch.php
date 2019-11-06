<?php
    
    // $options['host'] = "115.146.93.173";
    // $options['port'] = 5984;
    // $options['user'] = "admin_test_walk";
    // $options['pass'] = "admin_test_walk";
    $file = file_get_contents("couchdb.txt");

    $opList = explode("\r\n",$file);

    $options['port'] = 5984;
    $options['host'] = $opList[0];
    $options['user'] = $opList[1];
    $options['pass'] = $opList[2];

    echo json_encode($options);

    // fclose($file);

    // Creating connection
    $couch = new CouchSimple($options);
    
    $couch->send("GET", "/");


    class CouchSimple
    {
        function CouchSimple($options)
        {
            foreach($options AS $key => $value)
            {
                $this->$key = $value;
            }
        }

        function send($method, $url, $post_data = NULL)
        {
            $s = fsockopen($this->host, $this->port, $errno, $errstr);
            if(!$s)
            {
                echo "$errno: $errstr\n";
                return false;
            }
            $request = "$method $url HTTP/1.0\r\nHost: $this->host\r\n";

            if ($this->user)
            {
                $request .= "Authorization: Basic ".base64_encode("$this->user:$this->pass")."\r\n";
            }
            if($post_data)
            {
                $request .= "Content-Length: ".strlen($post_data)."\r\n\r\n";
                $request .= "$post_data\r\n";
            }
            else
            {
                $request .= "\r\n";
            }

            fwrite($s, $request);
            $response = "";
            while(!feof($s))
            {
                $response .= fgets($s);
            }
            list($this->headers, $this->body) = explode("\r\n\r\n", $response);
            return $this->body;
        }
    }
?>