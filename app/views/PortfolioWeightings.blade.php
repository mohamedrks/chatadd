<table class="table table-bordered">
    <tr>
        <td>
            <h2> Portfolio Weighting </h2>

        </td>
    </tr>
    <tr>
        <td>
            <h4><b> {{ $portfolio->name }} </b></h4>
        </td>
    </tr>
    <tr style="background-color: #BBBBBB">
        <th><h4>Contract Date</h4></th>
        <th><h4>Contract Note Number</h4></th>
        <th><h4></h4>Code</th></th>
    </tr>
    <tbody>
    @foreach ( $portfolioWeight as $row)
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
</div>

</div>
