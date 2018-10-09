<?php

if (!defined('ROOT')) {
    die('Access is denied.');
}

?>

<?php if ($ajax == 1) { ?>

    <?php
    if (!empty($page)) {
        if (file_exists(FOLDER_PAGE.'/'.$page.'/'.$page.'.php')) {
            include_once FOLDER_PAGE.'/'.$page.'/'.$page.'.php';
        } else {
            echo $page.' Page not found.';   
        }
    } else {
        if (file_exists(FOLDER_PAGE.'/home/home.php')) {
            include_once FOLDER_PAGE.'/home/home.php';
        } else {
            echo 'Home Page not found.';   
        }
    }
    ?>
    
<?php } else { ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>RSUP Sanglah Denpasar</title>
<style>
    * {
        font-size: 11px;
    }
</style>
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td height="40" valign="top" colspan="2">
                <?php
                if (file_exists(FOLDER_TEMPLATE.'/'.'header'.'.php')) {
                    include_once FOLDER_TEMPLATE.'/'.'header'.'.php';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td width="10%" valign="top">
                <?php
                if (file_exists(FOLDER_TEMPLATE.'/'.'menu'.'.php')) {
                    include_once FOLDER_TEMPLATE.'/'.'menu'.'.php';
                }
                ?>
            </td>
            <td width="90%" valign="top">
                <?php
                if (!empty($page)) {
                    if (file_exists(FOLDER_PAGE.'/'.$page.'/'.$page.'.php')) {
                        include_once FOLDER_PAGE.'/'.$page.'/'.$page.'.php';
                    } else {
                        echo $page.' Page not found.';   
                    }
                } else {
                    if (file_exists(FOLDER_PAGE.'/home/home.php')) {
                        include_once FOLDER_PAGE.'/home/home.php';
                    } else {
                        echo 'Home Page not found.';   
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td valign="top" colspan="2">
                <?php
                if (file_exists(FOLDER_TEMPLATE.'/'.'footer'.'.php')) {
                    include_once FOLDER_TEMPLATE.'/'.'footer'.'.php';
                }
                ?>
            </td>
        </tr>
    </table>
    <script type="text/javascript">
        function printElem(divId) {
            var content = document.getElementById(divId).innerHTML;
            var mywindow = window.open('', 'Print', 'height=600,width=800');

            mywindow.document.write('<html><head><title>Print</title>');
            mywindow.document.write('</head><body >');
            mywindow.document.write(content);
            mywindow.document.write('</body></html>');

            mywindow.document.close();
            mywindow.focus()
            mywindow.print();
            mywindow.close();
            return true;
        }
    </script>
    <script type="text/javascript">
        var tableToExcel = (function() {
            var uri = 'data:application/vnd.ms-excel;base64,', 
            template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>', 
            base64 = function(s) { 
                return window.btoa(unescape(encodeURIComponent(s))) 
            }, 
            format = function(s, c) { 
                return s.replace(/{(\w+)}/g, function(m, p) { 
                    return c[p]; 
                }) 
            }
            return function(table, name) {
                if (!table.nodeType) 
                    table = document.getElementById(table)
                var ctx = {
                    worksheet: name || 'Worksheet', table: table.innerHTML
                }
                window.location.href = uri + base64(format(template, ctx))
            }
        })()
    </script>
</body>
</html>
<?php } ?>