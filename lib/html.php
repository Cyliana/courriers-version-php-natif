<?php
    class HTML
    {
        public $HTML;

        public function __construct($title='')
        {
            $this->HTML = '';
            if($title != '')
            {
                $this->HTML = file_get_contents('inc/head.php');
                $this->HTML = str_replace("<title></title>","<title>$title</title>",$this->HTML);
            }
        }

        // --------------------------------------
        private function arrayToStr($array,$type='string')
        {
            $a = [];
            if($type == 'string')
            {
                foreach($array as $f=>$v)
                {
                    array_push($a,"$f=\"$v\"");
                }
            }
            if($type == 'code')
            {
                foreach($array as $f=>$v)
                {
                    array_push($a,"\$$f=\"$v\";");
                }
                error_log(json_encode($a));
            }
            return(implode(' ',$a));
        }
 
        // --------------------------------------
        public function innerHTML($HTML)
        {
            $this->HTML .= $HTML;
        }

        // --------------------------------------
        public function header_($attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<header id=\"header\" $a>";
        }
        public function _header()
        {
            $this->HTML .= "</header>";
        }
        public function header($innerHTML='', $attributes=[])
        {
            $this->header_($attributes);
            $this->innerHTML($innerHTML);
            $this->_header();
        }

        // --------------------------------------
        public function main_($attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<main id=\"main\" $a>";
        }
        public function _main()
        {
            $this->HTML .= "</main>";
        }
        public function main($innerHTML='', $attributes=[])
        {
            $this->main_($attributes);
            $this->innerHTML($innerHTML);
            $this->_main();
        }

        // --------------------------------------
        public function footer_($attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<footer id=\"footer\" $a>";
        }
        public function _footer()
        {
            $this->HTML .= "</footer>";
        }
        public function footer($innerHTML='', $attributes=[])
        {
            $this->footer_($attributes);
            $this->innerHTML($innerHTML);
            $this->_footer();
        }

        // --------------------------------------
        public function space()
        {
            $this->HTML.= " ";
        }

        // --------------------------------------
        public function nbsp()
        {
            $this->HTML.= "&nbsp;";
        }

        // --------------------------------------
        public function br()
        {
            $this->HTML.= "<br/>";
        }

        // --------------------------------------
        public function hr()
        {
            $this->HTML.= "<hr/>";
        }

        // --------------------------------------
        public function div_($id='', $attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<div id=\"$id\" $a>";
        }
        public function _div()
        {
            $this->HTML .= "</div>";
        }
        public function div($id='', $innerHTML='', $attributes=[])
        {
            $this->div_($id='', $attributes);
            $this->innerHTML($innerHTML);
            $this->_div();
        }

        // --------------------------------------
        public function p_($id='', $attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<p id=\"$id\" $a>";
        }
        public function _p()
        {
            $this->HTML .= "</p>";
        }
        public function p($id='', $innerHTML='', $attributes=[])
        {
            $this->div_($id='', $attributes);
            $this->innerHTML($innerHTML);
            $this->_div();
        }

        // --------------------------------------
        public function form_($id='', $action="", $method='POST', $attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<form id=\"$id\" method=\"$method\" action=\"$action\" $a>";
        }
        public function _form()
        {
            $this->HTML .= "</form>";
        }
        public function form($id='', $action, $method='POST', $innerHTML='', $attributes=[])
        {
            $this->form_($id, $action, $method, $attributes);
            $this->innerHTML($innerHTML);
            $this->_form();
        }

        // --------------------------------------
        public function label($for,$caption,$attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<label for=\"$for\" $a>$caption</label>";
        }

        // --------------------------------------
        public function input($id='', $name='', $type='text', $value='', $attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<input id=\"$id\" name=\"$name\" type=\"$type\" value=\"$value\" $a />";
        }

        // --------------------------------------
        public function textarea($id='', $name='', $text='', $attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<textarea id=\"$id\" name=\"$name\" $a>$text</textarea>";
        }

        // --------------------------------------
        public function select($id='', $name='', $values=[], $default='', $attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<select id=\"$id\" name=\"$name\" $a >";
            $this->HTML .= "<option value=\"\"></option>";
            foreach($values as $value=>$caption)
            {
                $selected = ($value == $default) ? "selected" : '';
                $this->HTML .= "<option value=\"$value\" $selected>$caption</option>";
            }
            $this->HTML .= "</select>";
        }

        // --------------------------------------
        public function fieldInput($id='', $name='', $type='text', $value='', $attributes=[])
        {
            //error_log("$id =====================================================****");
            eval($this->arrayToStr($attributes,'code'));
            $this->div_('',['class'=>'fieldset']);
            $this->label($name,$placeholder);
            $this->input($id, $name, $type, $value, $attributes);
            $this->_div();
            //error_log("$id ----------------------------------------------------------");
        }

        // --------------------------------------
        public function fieldTextarea($id='', $name='', $text='', $attributes=[])
        {
            eval($this->arrayToStr($attributes,'code'));
            $this->div_('',['class'=>'fieldset']);
            $this->label($name,$placeholder);
            $this->textarea($id, $name, $text, $attributes);
            $this->_div();
        }

        // --------------------------------------
        public function fieldSelect($id='', $name='', $values=[], $default='', $attributes=[])
        {
            eval($this->arrayToStr($attributes,'code'));
            $this->div_('',['class'=>'fieldset']);
            $this->label($name,$placeholder);
            $this->select($id, $name, $values, $default, $attributes);
            $this->_div();
        }
        
        // --------------------------------------
        public function submit($id='', $value='', $attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<input id=\"$id\" type=\"submit\" value=\"$value\" $a />";
        }

        // --------------------------------------
        public function button($id='', $value='', $attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<input id=\"$id\" type=\"button\" value=\"$value\" $a />";
        }

        // --------------------------------------
        public function a_($id='', $href='#', $attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<a id=\"$id\" href=\"$href\" $a>";
        }
        public function _a()
        {
            $this->HTML .= "</a>";
        }
        public function a($id='', $href='#', $innerHTML='', $attributes=[])
        {
            $this->a_($id, $href, $attributes);
            $this->innerHTML($innerHTML);
            $this->_a();
        }

        // --------------------------------------
        public function table_($id='', $attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<table id=\"$id\" $a>";
        }
        public function _table()
        {
            $this->HTML .= "</table>";
        }
        public function table($id='', $innerHTML='', $attributes=[])
        {
            $this->table_($id, $attributes);
            $this->innerHTML($innerHTML);
            $this->_table();
        }

        // --------------------------------------
        public function thead_($id='', $attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<thead id=\"$id\" $a>";
        }
        public function _thead()
        {
            $this->HTML .= "</thead>";
        }
        public function thead($id='', $innerHTML='', $attributes=[])
        {
            $this->thead_($id, $attributes);
            $this->innerHTML($innerHTML);
            $this->_thead();
        }

        // --------------------------------------
        public function tbody_($id='', $attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<tbody id=\"$id\" $a>";
        }
        public function _tbody()
        {
            $this->HTML .= "</tbody>";
        }
        public function tbody($id='', $innerHTML='', $attributes=[])
        {
            $this->tbody_($id, $attributes);
            $this->innerHTML($innerHTML);
            $this->_tbody();
        }

        // --------------------------------------
        public function tfoot_($id='', $attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<tfoot id=\"$id\" $a>";
        }
        public function _tfoot()
        {
            $this->HTML .= "</tfoot>";
        }
        public function tfoot($id='', $innerHTML='', $attributes=[])
        {
            $this->tfoot_($id, $attributes);
            $this->innerHTML($innerHTML);
            $this->_tfoot();
        }

        // --------------------------------------
        public function tr_($id='', $attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<tr id=\"$id\" $a>";
        }
        public function _tr()
        {
            $this->HTML .= "</tr>";
        }
        public function tr($id='', $innerHTML='', $attributes=[])
        {
            $this->tr_($id, $attributes);
            $this->innerHTML($innerHTML);
            $this->_tr();
        }

        // --------------------------------------
        public function th_($id='', $attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<th id=\"$id\" $a>";
        }
        public function _th()
        {
            $this->HTML .= "</th>";
        }
        public function th($id='', $innerHTML='', $attributes=[])
        {
            $this->th_($id, $attributes);
            $this->innerHTML($innerHTML);
            $this->_th();
        }

        // --------------------------------------
        public function td_($id='', $attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            $this->HTML .= "<td id=\"$id\" $a>";
        }
        public function _td()
        {
            $this->HTML .= "</td>";
        }
        public function td($id='', $innerHTML='', $attributes=[])
        {
            $this->td_($id, $attributes);
            $this->innerHTML($innerHTML);
            $this->_td();
        }

        // --------------------------------------
        public function tableHeaders($headers)
        {
            $this->HTML .= "<tr>";
            $this->HTML .= "<td>&nbsp;</td>";
            foreach($headers as $header)
            {
                $this->HTML .= "<th>$header</th>";
            }
            $this->HTML .= "</tr>";
        }

        // --------------------------------------
        public function tableRows($tableId, $rows, $attributes=[])
        {
            $a = $this->arrayToStr($attributes);
            foreach($rows as $rk=>$row)
            {
                $this->HTML .= "<tr id=\"{$tableId}_{$rk}\" $a>";
                $this->HTML .= "<td><input id=\"check_{$row[0]}\" name=\"{$tableId}[]\" value=\"{$row[0]}\" type=\"checkbox\" /></td>";
                foreach($row as $field)
                {
                    $this->HTML .= "<td title=\"$field\">$field</td>";
                }
                $this->HTML .= "</tr>";
            }
        }

        public function tableFilled($id='',$headers,$records,$attributes=[])
        {
            $this->table_($id);
            $this->thead_();
            $this->tableHeaders($headers);
            $this->_thead();
            $this->tbody_();
            $this->tableRows($id,$records,$attributes);
            $this->_tbody();
            $this->_table();
        }

        // ======================================
        public function output()
        {
            $this->HTML.= file_get_contents('inc/foot.php');
            print($this->HTML);
        }
    }
