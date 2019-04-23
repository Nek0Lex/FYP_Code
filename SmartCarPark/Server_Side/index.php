<?php
require_once('init.php');
$id = intval(1);
// Check the ID exists and get park information.

$pstmt = DB::get()->prepare("SELECT *, (SELECT count(*) FROM spaces WHERE space_park_id = park_id) AS ps,
	(" . get_num_space_query("park_id") . ") AS spaces FROM parks WHERE park_id = ?");
$pstmt->bindValue(1, $id, PDO::PARAM_INT);
$pstmt->execute();

// Get the carpark data
$park = $pstmt->fetch(PDO::FETCH_ASSOC);

$nav_selected = 2;

require_once('include/header.php');

?>


<div class="container-fluid mt-4">
    <div class="row mt-5">
        <div class="col-5">
            <div class="card">
                <h5 class="card-header">Car Park</h5>
                <div class="card-body" style="background-color: #E4E4E4">
                    <?php
                    $query = "SELECT * 
                              FROM spaces a
                              LEFT JOIN (
                                  SELECT *
                                  FROM updates b
                                  WHERE update_time = (
                                    SELECT max( update_time )
                                    FROM updates um
                                    WHERE um.update_space_id = b.update_space_id
                                  )
                                  GROUP BY b.update_space_id
                              ) b 
                              ON a.space_id = b.update_space_id
                              WHERE space_park_id = " . $id;

                    $stmt = DB::get()->query($query);
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    ?>
                    <canvas id="carParkSpace" width="600px" height="450px"></canvas>
                    <script>
                        function Shape(x, y, w, h, FillColor, EmptyColor, ReservedColor, MaintenanceColor) {
                            this.x = x;
                            this.y = y;
                            this.w = w;
                            this.h = h;
                            this.FillColor = FillColor;
                            this.EmptyColor = EmptyColor;
                            this.ReservedColor = ReservedColor;
                            this.MaintenanceColor = MaintenanceColor;
                        }

                        // get canvas element.
                        var c = document.getElementById('carParkSpace');

                        // check if context exist
                        if (c.getContext) {
                            let myRect = [];
                            let x = 20, y = 20;
                            let w = x + 90, h = y + 170;
                            for (let row = 0; row < 2; row++) {
                                for (let col = 0; col < 4; col++) {
                                    myRect.push(new Shape(x, y, w, h, "#800000", "#32CD32", "#75ccdd", "#FFD700"));
                                    x += 130
                                }
                                x = 20; //init another row head
                                y += 210; // change another row
                            }
                            let ctx = c.getContext('2d');
                            let savedRect;
                            for (let i = 0; i < myRect.length; i++) { //init car park
                                savedRect = myRect[i];
                                ctx.rect(savedRect.x, savedRect.y, savedRect.w, savedRect.h);
                                ctx.stroke();
                                ctx.fillStyle = savedRect.EmptyColor;
                                ctx.fillRect(savedRect.x, savedRect.y, savedRect.w, savedRect.h);
                            }

                            //show occupied park space
                            let update_status = new Map();
                            <?php
                            foreach ($rows as $row) {
                            $i++;
                            ?>
                            update_status.set(<?php echo $i?>, [<?php echo $row['update_status']; ?>, '<?php echo $row['space_operation']; ?>']);
                            console.log(update_status.get(<?php echo $i?>));
                            <?php }?>


                            //show occupied park space (canvas)
                            for (let j = 1; j <= update_status.size; j++) {
                                if (update_status.get(j)[0] === 1) {
                                    savedRect = myRect[j - 1];
                                    ctx.fillStyle = savedRect.FillColor;
                                    ctx.fillRect(savedRect.x, savedRect.y, savedRect.w, savedRect.h);
                                } else if (update_status.get(j)[0] === 0 && update_status.get(j)[1] === "reserved"){
                                    savedRect = myRect[j - 1];
                                    ctx.fillStyle = savedRect.ReservedColor;
                                    ctx.fillRect(savedRect.x, savedRect.y, savedRect.w, savedRect.h);
                                } else if (update_status.get(j)[0] === 0 && update_status.get(j)[1] === "maintenance"){
                                    savedRect = myRect[j - 1];
                                    ctx.fillStyle = savedRect.MaintenanceColor;
                                    ctx.fillRect(savedRect.x, savedRect.y, savedRect.w, savedRect.h);
                                }
                            }

                            //init park space number
                            for (let i = 0; i < myRect.length; i++) {
                                savedRect = myRect[i];
                                ctx.fillStyle = "#000000";
                                ctx.font = "30px Arial";
                                ctx.fillText(i + 1, savedRect.x + 20, savedRect.y + 40);
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <h5 class="card-header text-center">Available Space</h5>
                <div class="card-body text-center">
                    <canvas id="myChart" width="100px" height=144px"></canvas>
                    <script> //canvas script
                        Chart.pluginService.register({
                            beforeDraw: function (chart) {
                                if (chart.config.options.elements.center) {
                                    //Get ctx from string
                                    var ctx = chart.chart.ctx;

                                    //Get options from the center object in options
                                    var centerConfig = chart.config.options.elements.center;
                                    var fontStyle = centerConfig.fontStyle || 'Arial';
                                    var txt = centerConfig.text;
                                    var color = centerConfig.color || '#000';
                                    var sidePadding = centerConfig.sidePadding || 20;
                                    var sidePaddingCalculated = (sidePadding / 100) * (chart.innerRadius * 2)
                                    //Start with a base font of 30px
                                    ctx.font = "30px " + fontStyle;

                                    //Get the width of the string and also the width of the element minus 10 to give it 5px side padding
                                    var stringWidth = ctx.measureText(txt).width;
                                    var elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;

                                    // Find out how much the font can grow in width.
                                    var widthRatio = elementWidth / stringWidth;
                                    var newFontSize = Math.floor(30 * widthRatio);
                                    var elementHeight = (chart.innerRadius * 2);

                                    // Pick a new font size so it will not be larger than the height of label.
                                    var fontSizeToUse = Math.min(newFontSize, elementHeight);

                                    //Set font settings to draw it correctly.
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'middle';
                                    var centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
                                    var centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
                                    ctx.font = fontSizeToUse + "px " + fontStyle;
                                    ctx.fillStyle = color;

                                    //Draw text in center
                                    ctx.fillText(txt, centerX, centerY);
                                }
                            }
                        });


                        var config = {
                            type: 'doughnut',
                            data: {
                                labels: [
                                    "Occupied",
                                    "Empty",
                                ],
                                datasets: [{
                                    data: [<?php echo $park['spaces'], ",", $park['ps'] - $park['spaces'] ?>],
                                    backgroundColor: [
                                        "#800000",
                                        "#32CD32",
                                    ],
                                    hoverBackgroundColor: [
                                        "#800000",
                                        "#32CD32",
                                    ]
                                }]
                            },
                            options: {
                                elements: {
                                    center: {
                                        text: '<?php echo $park['ps'] - $park['spaces'] ?>',
                                        color: '#000000', // Default is #000000
                                        fontStyle: 'Arial', // Default is Arial
                                        sidePadding: 60 // Defualt is 20 (as a percentage)
                                    }
                                },
                                animation: {
                                    duration: 0
                                }
                            }
                        };


                        var ctx = document.getElementById("myChart").getContext("2d");
                        var myChart = new Chart(ctx, config);
                    </script>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <h5 class="card-header text-center">Space Info</h5>
                <table id="spaceInfo" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th>Space ID</th>
                        <th>Status</th>
                        <th>Book</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 0;
                    foreach ($rows as $row) {
                        $i++;
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td>
                                <?php if ($row['update_status'] == 1) {?>
                                    <div class="alert alert-danger">Occupied</div>
                                <?php } else if ($row['space_operation'] == "reserved"){ ?>
                                    <div class="alert alert-info">Reserved</div>
                                <?php } else if ($row['space_operation'] == "maintenance"){?>
                                    <div class="alert alert-warning">Maintenance</div>
                                <?php } else {?>
                                    <div class="alert alert-success">Empty</div>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if ($row['update_status'] == 1) {?>
                                    <button type="button" class="btn btn-primary" aria-disabled="true" data-toggle="modal" data-target="#takeCar_form" id="operation_btn">Out</button>
                                <?php } else if ($row['space_operation'] != null){ ?>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#operation_cancel_form" id="operation_btn">Cancel</button>
                                <?php } else {?>
                                     <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#operation_form" id="operation_btn">reserve</button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script> //operation form
        let table = initTable();
        var data = table.rows().data();
        // console.log(data.length);

        $('#spaceInfo tbody').on('click', '#operation_btn', function () {
            var data = table.row($(this).parents('tr')).data();
            //alert( "Car park id is  "+ data[0] );
            document.getElementById('parkSpaceId').value = data[0];
            document.getElementById('parkSpaceId_cancel').value = data[0];
            document.getElementById('parkSpaceId_takeCar').value = data[0];
        });
    </script>

    <div class="modal fade" id="operation_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Operation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="handleParkingSpaceAction.php" method="get">
                    <div class="modal-body">
                        Parking Space ID: <input type="text" readonly class="form-control-plaintext" id="parkSpaceId"
                                                 name="parkSpaceId">
                        <br>
                        Action:
                        <select class="custom-select my-1 mr-sm-2" id="selectAction" name="selectAction">
                            <option value="reserved" selected>Reserved</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                        <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-primary" value="submit">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="operation_cancel_form" tabindex="-1" role="dialog" aria-labelledby="operationCancelFormTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="operationCancelFormTitle">Operation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="handleParkingSpaceAction.php" method="get">
                    <div class="modal-body">
                        Parking Space ID: <input type="text" readonly class="form-control-plaintext" id="parkSpaceId_cancel"
                                                 name="parkSpaceId_cancel">
                        <br>
                        Do you want to cancel this parking space action?
                        <input type="hidden" type="text" class="form-control-plaintext" id="selectAction"
                               name="selectAction" value="cancel">
                        <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-primary" value="Confirm">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="takeCar_form" tabindex="-1" role="dialog" aria-labelledby="operationCancelFormTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="operationCancelFormTitle">Operation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="handleTakeCarAction.php" method="get">
                    <div class="modal-body">
                        Parking Space ID: <input type="text" readonly class="form-control-plaintext" id="parkSpaceId_takeCar"
                                                 name="parkSpaceId_takeCar">
                        <br>
                        Do you want take this car?
                        <input type="hidden" type="text" class="form-control-plaintext" id="selectAction" name="selectAction" value="cancel">
                        <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-primary" value="Confirm">
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="row ml-1 mt-2">
        <?php if (isset($_GET['refresh'])) { ?>
            <a class="btn btn-danger" href="?id=<?php echo $park['park_id']; ?>">
                Turn off Auto Refresh</a>
            <script>
                function refresh() {
                    window.location.reload(true);
                }

                setTimeout(refresh, 500);
            </script>
        <?php } else { ?>
            <a class="btn btn-success" href="?id=<?php echo $park['park_id']; ?>&refresh">
                Turn on Auto Refresh</a>
        <?php } ?>
    </div>
</div>
<!-- /#page-content-wrapper -->

</div>


<?php require_once('include/footer.php') ?>
