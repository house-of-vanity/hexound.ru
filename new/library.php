
<?php
//$query = "SELECT modules.name as mod_name, modules.date, users.name as user_name FROM `modules` LEFT JOIN users ON modules.uploaded_by = users.id";
$query = "SELECT 
m.name as mod_name, 
m.date, 
m.conv_path, 
u.name as user_name 
FROM modules m LEFT JOIN users u ON m.uploaded_by = u.id";
$raw = mysqli_query($con, $query); 
?>
<style>
table.scroll {
    width: 100%; /* Optional */
    /* border-collapse: collapse; */
    border-spacing: 0;
    border: 2px solid black;
}

table.scroll tbody,
table.scroll thead { display: block; }

thead tr th { 
    height: 30px;
    line-height: 30px;
    /* text-align: left; */
}

table.scroll tbody {
    height: 400px;
    line-height: 30px;
    overflow-y: auto;
    overflow-x: hidden;
}

tr td {
    min-width: 150px;
    padding: 10px;
    border-bottom: 1px solid grey;
}

tbody { border-top: 2px solid black; }

tbody td, thead th {
    /* width: 20%; */ /* Optional */
    border-right: 1px solid black;
    /* white-space: nowrap; */
}

tbody td:last-child, thead th:last-child {
    border-right: none;
}

#title_user, #title_date {
    font-size: 0.8em;
    line-height: 10px;
}
</style>
<table class="scroll">
    <thead>
        <tr>
            <th>Title</th>
        </tr>
    </thead>
    <tbody>
<?php
while($modules = mysqli_fetch_assoc($raw))
{
    echo('<tr>');
    echo("<td>".$modules['mod_name']."<br><span id='title_user'>".$modules['user_name']."</span> <span id=title_date>".$modules['date']."</span></td>");
    echo('</tr>');
}
?>
    </tbody>
</table>
<script>
// Change the selector if needed
var $table = $('table.scroll'),
    $bodyCells = $table.find('tbody tr:first').children(),
    colWidth;

// Adjust the width of thead cells when window resizes
$(window).resize(function() {
    // Get the tbody columns width array
    colWidth = $bodyCells.map(function() {
        return $(this).width();
    }).get();
    
    // Set the width of thead columns
    $table.find('thead tr').children().each(function(i, v) {
        $(v).width(colWidth[i] + 18);
    });    
}).resize(); // Trigger resize handler
</script>
