<!--
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/9/15
 * Time: 4:32 PM
 */-->


<table style="width: 800px">


    <tr>
        <td style="text-align: center"><h2> CURRENT PORTFOLIO </h2></td>
    </tr>
    <tr>
        <td></td>
        <td style="text-align: center"><h5><b>{{ $portfolio->name }}</b></h5></td>
        <td style="text-align: right">{{ $portfolioValue[0]->totalPortfolioValue }}</td>
    </tr>
    <tr style="background-color: #BBBBBB">
        <th>Code</th>
        <th>Name & Description</th>
        <th>Total Qty</th>
        <th>Closing Price</th>
        <th>Market Value</th>
        <th>Net Div</th>
        <th>Cost Base Div Yield</th>
        <th>Annual Income</th>
        <th>Franked Credits</th>
        <th>Gross Income</th>
        <th>Cost Base Gross Yield</th>
        <th>Franking Percent</th>
    </tr>
    <tbody>

    @foreach ($currentPortfolio as $row)
    <tr>
        <td>
            {{ $row->code}}
        </td>
        <td>
            {{ $row->name}}
        </td>
        <td>
            {{ $row->balance_shares}}
        </td>
        <td>
            {{ ROUND($row->closingPrice,2) }}
        </td>
        <td>
            {{ ROUND($row->MarketValue,2) }}
        </td>
        <td>
            {{ ROUND($row->NetDiv,2) }}
        </td>
        <td>

        </td>
        <td>
            {{ ROUND($row->AnnualIncome,2) }}
        </td>
        <td>
            {{ ROUND($row->FrankedCredits,2) }}
        </td>
        <td>
            {{ ROUND($row->GrossIncome,2) }}
        </td>
        <td>
            {{ ROUND($row->CostBaseGrossYield,2) }}
        </td>
        <td>

        </td>
    </tr>

    @endforeach

    </tbody>
</table>
