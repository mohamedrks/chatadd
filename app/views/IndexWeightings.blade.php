
    <table>
        <tr>
            <td><h2> Index Weighting </h2></td>
        </tr>
        <tr>
            <td><h5><b> {{ $portfolio->name }} </b></h5></td>
        </tr>
        <tr style="background-color: #BBBBBB">
            <th><h4>Contract Date</h4></th>
            <th><h4>Contract Note Number</h4></th>
            <th><h4>Code</h4></th>
        </tr>

        <tbody>
        @foreach ($indexWeight as $row)
        <tr>
            <td>
                {{ $row['name'] }}
            </td>
            <td>
                {{ "$".number_format($row['marketCap'],2) }}
            </td>
            <td>
                {{ ROUND($row['percentage'],2)."%" }}
            </td>
        </tr>

        @endforeach

        </tbody>

    </table>