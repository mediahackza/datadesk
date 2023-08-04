<?php

include_once('utils.php');

    class Account {

        private $email;
        private $name;
        private $surname;
        private $id;
        private $token;
        private $logged_in = false;
        private $bookmarks = array();

        function __constructor() {

        }

        function set_data_from_row($row) {
            // var_dump($row);
            $this->set_email($row['email']);
            $this->set_first_name($row['name']);
            $this->set_last_name($row['surname']);
            $this->set_id($row['id']);
            if (isset($_POST['token'])) {
                $this->set_token($row['token']);
            }
            $this->fetch_bookmarks();
        }

        function refresh() {
            $this->bookmarks = array();
            $this->fetch_bookmarks();
            return $this;
        }

        function logout() {
            if (isset($_COOKIE['login_session']) || isset($_SESSION['users'])) {
                unset($_SESSION['user']);
                setcookie('login_session', '', time() - 3600, '/');
            }
        }

        function fetch_bookmarks() {
            global $bookmarks;

            $bookmarks->columns(array('table_id'));
            $bookmarks->clear_where();
            $bookmarks->add_where('user_id', $this->get_id(), '=');
            $bookmarks->select();

            if ($res = $bookmarks->query()) {
                foreach($res as $row) {
                    $this->add_bookmark($row['table_id']);
                }
            }

            return false;
        }

        function add_bookmark($table_id) {
            $this->bookmarks[] = $table_id;
        }

        function is_bookmarked($id) {
            foreach($this->bookmarks as $bookmark) {
                if ($bookmark == $id) {
                    return true;
                }
            }
            return false;
        }

        function log_in() {
            $this->logged_in = true;
        }

        function check_logged_in() {
            if ($this->logged_in) {
                return true;
            }            
        }

        function set_first_name($name) {
            $this->name = $name;
        }

        function set_last_name($surname) {
            $this->surname = $surname;
        }

        function set_id($id) {
            $this->id = $id;
        }

        function get_id() {
            return $this->id;
        }

        function set_token($token) {
            $this->token = $token;
        }

        function set_email($email) {
            $this->email = $email;
        }

        function get_email() {
            return $this->email;
        }

        function get_full_name() {
            return $this->name . " " .$this->surname;
        }

        function attempt_login($email, $password) {
            $password = encryption::encrypt($password);
            global $users;
            $users->columns(array('*'));
            $users->clear_where();
            $users->add_where('email', $email, '=');
            $users->add_where('password', $password, '=');

            $users->select();
            if ($res = $users->query()) {
                if (count($res) == 0) {
                    return false;
                }
                $this->set_data_from_row($res[0]);
                $this->log_in();
                return true;
            } else {
                return false;
            }
        }

        function attempt_login_token() {
            if ($cookie = $this->get_cookie()) {
                global $users;
                $data = Utils::decode_token($cookie);
                $id = $data['id'];
                $token = $data['token'];
                $users->columns(array('*'));
                $users->add_where('id', $id, '=');
                $users->add_where('token', $token, '=');
                $users->select();
                if ($res = $users->query()) {

                    if (count($res) == 0) {
                        return false;
                    }
                    $this->set_data_from_row($res[0]);
                    echo "logged in successfully from cookie";
                    return true; 
                }
                
            } 
        }

        // this chhecks to see if the user has a cookie set to maintain their accout
        // if there is a cookie it will return that cookie
        // if not it will return false
        function get_cookie() {
            if (isset($_COOKIE['login_session'])) {
                return $_COOKIE['login_session'];
            }
            return false;
        }


    }


?>