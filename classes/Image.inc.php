<?
class Image {
             function show(&$vars) {
                return $this->data['url'];
             }

             function Image($name) {
                global $mysql;
                $query = "SELECT id, name, url FROM Image WHERE name='$name';";
                $array = $mysql->executeSql($query);
                $this->data = $array;
             }
}
?>
