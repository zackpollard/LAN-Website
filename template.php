<?php

    class Template {
    
        private $subtitle;
        private $navbar = array();
        private $parent;
        private $refresh = false;
        
        public function __construct($parent) {
            $this->parent = $parent;
            $this->subtitle = $this->parent->settings->getSetting("default_title");
        }
        
        public function setRefresh($bool) {
            $this->refresh = $bool;
        }
        
        public function setSubTitle($title) {
            $this->subtitle = $title;
        }
        
        public function addNavElement($url, $name, $page) {
            $this->navbar[] = array($url, $name, $page);
        }
        
        /**
         * Outputs the template. Options:
         *   - 'template' -> template file to load
         *   - 'data' -> array of data to be added to the databag
         *   - 'styles' -> array of styles to be included
         *   - 'scripts' -> array of scripts to be included
         */
        public function outputTemplate($options = "") {
            
            $DataBag = array();
        
            //Load header options
            $DataBag["title"] = ucwords($this->subtitle) . " | LSUCS LAN" . $this->parent->settings->getSetting("lan_number");
            
            $DataBag["navbar"] = $this->navbar;
            $DataBag["countdown"] = $this->parent->settings->getSetting("enable_timer");
            $DataBag["subtitle"] = $this->subtitle;
            $DataBag["page"] = $this->parent->page;
            $DataBag["refresh"] = $this->refresh;
            $DataBag["loggedin"] = $this->parent->auth->isLoggedIn();
            $userData = $this->parent->auth->getActiveUserData();
            $DataBag["username"] = $userData["xenforo"]["username"];
            $DataBag["lan"] = $this->parent->settings->getSetting("lan_number");
            $DataBag["datestring"] = date('d', strtotime($this->parent->settings->getSetting("lan_start_date"))) . date("-dS M Y", strtotime($this->parent->settings->getSetting("lan_end_date")));
            $DataBag["styles"] = array();
            $DataBag["scripts"] = array();
            
            //Load template options
            if (is_array($options) && isset($options['data'])) $DataBag = array_merge($DataBag, $options['data']);
            if (is_array($options) && isset($options['styles'])) {
                if (is_array($options['styles'])) $DataBag["styles"] = $options["styles"];
                else $DataBag["styles"] = array($options["styles"]);
            } else {
                $DataBag["styles"] = array($this->parent->page . ".css");
            }
            if (is_array($options) && isset($options['scripts'])) {
                if (is_array($options['scripts'])) $DataBag["scripts"] = $options["scripts"];
                else $DataBag["scripts"] = array($options["scripts"]);
            } else {
                $DataBag["scripts"] = array($this->parent->page . ".js");
            }
            
            //Include header
            include("templates/header.tmpl");
            
            //Include template
            if (is_array($options) && isset($options["template"])) include("templates/" . $options["template"] . ".tmpl");
            else if (!is_array($options) && !empty($options)) include("templates/" . $options . ".tmpl");
            else if (!empty($options["content"])) echo $options["content"];
            
            //Include footer
            include("templates/footer.tmpl");
            
        }

    
    }

?>