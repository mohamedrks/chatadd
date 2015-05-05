<!doctype html>
<html>
<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
<div class="container">
    <div class="text-center"><h2> Index Weighting </h2></div>
    <div class="row">
        <div class="col-md-9 text-primary "><h5><b> {{ $portfolio }} </b></h5></div>
        <div>
            <table class="table table-bordered">

                <thead>
                <tr style="background-color: #BBBBBB">
                    <th>Contract Date</th>
                    <th>Contract Note Number</th>
                    <th>Code</th>
                </tr>
                </thead>

                <tbody>

                <tr>
                    <td>
                        Helo
                    </td>
                    <td>
                        This is
                    </td>
                    <td>
                        Test
                    </td>
                </tr>


                </tbody>

            </table>
        </div>

        <div>
            <?php echo $child; ?>
        </div>
    </div>

</div>


</body>
</html>
