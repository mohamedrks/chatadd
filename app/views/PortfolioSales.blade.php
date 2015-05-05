<!--
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/9/15
 * Time: 4:32 PM
 */-->



<!--        </div>-->
<table style="width: 800px">
    <tr>
        <td style="text-align: center"><h2> SALES </h2></td>
    </tr>
    <tr>
        <td style="text-align: left"><h5> {{ $portfolio->name }}</h5></td>
        <td></td>
    </tr>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th style="background-color: #BEE9EA">{{ $totalNetProceeds }}</th>
        <th style="background-color: #BEE9EA">{{ $totalPurchaseCost }}</th>
        <th style="background-color: #BEE9EA">{{ $totalProfitOrLoss }}</th>
    </tr>
    <tr style="background-color: #BBBBBB">
        <th>Contract Date</th>
        <th>Contract Note Number</th>
        <th>Code</th>
        <th>Description</th>
        <th>Action</th>
        <th>Quantity</th>
        <th>Avg. Price</th>
        <th>Brkg.</th>
        <th>Fees</th>
        <th>G.S.T</th>
        <th>Net Proceeds</th>
        <th>Purchase Costs</th>
        <th>Profit / Loss</th>
    </tr>
    <tbody>

    @foreach ($sales as $row)
    <tr>
        <td>
            {{ $row->created_at}}
        </td>
        <td>

        </td>
        <td>
            {{ $row->code}}
        </td>
        <td>

        </td>
        <td>
            {{ $row->transaction_type}}
        </td>
        <td>
            {{ $row->Quantity}}
        </td>
        <td>
            {{ $row->AveragePrice}}
        </td>
        <td>

        </td>
        <td>

        </td>
        <td>

        </td>
        <td>
            {{ $row->NetProceeds }}
        </td>

        <td>
            {{$row->PurchaseCost}}
        </td>
        <td>
            {{ $row->ProfitOrLoss }}
        </td>
    </tr>

    @endforeach

    </tbody>

</table>

