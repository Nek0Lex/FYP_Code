<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?php echo Conf::TITLE ?></title>
    <link rel="icon" href="../asset/favicon.ico"/>
    <link rel="stylesheet" href="asset/css/simple-sidebar.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4-4.1.1/dt-1.10.18/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/dt-1.10.18/datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>

    <script> //datatable script
        $(document).ready(function () {
            initTable();
        });

        function initTable(){
            return $('#spaceInfo').DataTable({
                "order": [[1,'asc']],
                "paging": false,
                "searching": false,
                "info": false,
                "retrieve": true
            });
        }

    </script>
</head>
<body>

<div class="d-flex toggled" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-dark text-white toggled" id="sidebar-wrapper">
        <div class="sidebar-heading"><img src="asset/logo2.png" alt="logo" style="width: 200px;"></div>
        <div class="list-group list-group-flush">
            <a href="#" class="list-group-item list-group-item-action bg-dark text-white">Main Dashboard</a>
            <a href="#" class="list-group-item list-group-item-action bg-dark text-white">Car Park Management</a>
        </div>
    </div>

    <!-- /#sidebar-wrapper -->
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-dark border-bottom text-white" style="background-color: #202225;">
            <button class="btn btn-info" id="menu-toggle">Menu</button>

            <!--            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">-->
            <!--                <span class="navbar-toggler-icon"></span>-->
            <!--            </button>-->

            <!--            <div class="collapse navbar-collapse" id="navbarSupportedContent">-->
            <!--                <ul class="navbar-nav ml-auto mt-2 mt-lg-0">-->
            <!--                    <li class="nav-item active">-->
            <!--                        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>-->
            <!--                    </li>-->
            <!--                    <li class="nav-item">-->
            <!--                        <a class="nav-link" href="#">Link</a>-->
            <!--                    </li>-->
            <!--                    <li class="nav-item dropdown">-->
            <!--                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
            <!--                            Dropdown-->
            <!--                        </a>-->
            <!--                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">-->
            <!--                            <a class="dropdown-item" href="#">Action</a>-->
            <!--                            <a class="dropdown-item" href="#">Another action</a>-->
            <!--                            <div class="dropdown-divider"></div>-->
            <!--                            <a class="dropdown-item" href="#">Something else here</a>-->
            <!--                        </div>-->
            <!--                    </li>-->
            <!--                </ul>-->
            <!--            </div>-->
        </nav>