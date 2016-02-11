<?php
require("../application/config/configurations.php");
require("../application/config/config-operations.php");
require("../application/global/generalfunctions.php");

configurations::configure();
$database = configurations::getDBObject();

if($_GET["flush"] and $_GET["flush"] == 1){
	$database->query("TRUNCATE table request_logs");
}
?>

    <table width="100%" style="border: 1px solid #D3D3D3;" >
        <tr style="background-color: #9FCF67;">
            <td>Log id</td>
            <td>Method</td>
            <td width="200">Posted Data</td>
            <td>Response</td>
            <td>Response in Time (Seconds)</td>
            <td>Remote IP</td>
            <td>Cookies</td>
            <td>Request Headers</td>
            <td>Response Headers</td>
            <td>Created Date</td>

        </tr>
        <?php if ($logs = $database->selectData("request_logs", "1", "created_date DESC")) {?>
        <?php
        for ($i = 0; $i < count($logs); $i++) {
            if ($i % 2 == 0) {
                $style = "style='background-color: #FFFFFF'";
            } else {
                $style = "style='background-color: #F1FFE0'";
            }
            ?>
            <tr <?php echo $style; ?>>
                <td><?php echo $logs[$i]['requestlog_id']; ?></td>
                <td><?php echo $logs[$i]['method']; ?></td>
                <td><?php
        foreach (unserialize($logs[$i]['posted_data']) as $k => $v) {
            echo $k . ":" . $v . "<br />";
        }
        ?></td>
                <td><?php echo htmlentities($logs[$i]['response']); ?></td>
                <td><?php echo htmlentities($logs[$i]['time']); ?></td>
                <td><?php echo $logs[$i]['remote_address']; ?></td>
                <td><?php
        foreach (unserialize($logs[$i]['cookies']) as $k => $v) {
            echo $k . ":" . $v . "<br />";
        }
        ?></td>
                <td><?php
        foreach (unserialize($logs[$i]['request_header']) as $k => $v) {
            echo $k . ":" . $v . "<br />";
        }
        ?></td>
                <td><?php
        foreach (unserialize($logs[$i]['response_header']) as $k => $v) {
            echo $k . ":" . $v . "<br />";
        }
        ?></td>
                <td><?php echo $logs[$i]['created_date']; ?></td>

            </tr>
            <?php
        }
        ?>
         <?php } ?>
    </table>
   