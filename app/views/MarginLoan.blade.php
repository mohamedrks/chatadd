<!--
/**
 * Created by PhpStorm.
 * User: rikazdev
 * Date: 3/9/15
 * Time: 4:32 PM
 */-->

<table style="width: 800px">
    <tr>
        <td></td>
        <td><h4><b>{{$portfolio->name}} </b></h4></td>
        <td>
            <h2> MARGIN LOAN </h2>
        </td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>
            <h4>Margin Loan : <h4>
        </td>
        <td>
            <h4> Last Financial Year : </h4>
        </td>
        <td>
            <h4> 2014 </h4>
        </td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>Purchases:</td>
        <td>{{ $purchase }}
        </td>
        <td>Open:</td>
        <td>{{$open}}</td>
    </tr>
    <tr>
        <td>Debits</td>
        <td>Interest:</td>
        <td>{{ $interest }}</td>
        <td>Close:</td>
        <td>{{$close}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td> Withdrawals:</td>
        <td>{{ $withdrawal }}</td>
        <td> Difference:</td>
        <td>{{$difference}}</td>

    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>{{$debitTotal}}</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td> Sales:</td>
        <td> {{ $sell }}</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td> Credits</td>
        <td> Payments:</td>
        <td>{{ $payment }}</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td>Dividends:</td>
        <td> {{ $dividend }} </td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>{{$creditTotal}}</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td> Change</td>
        <td> Balance:</td>
        <td>{{$change}}</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><h4>Date</h4></td>
        <td><h4>Description</h4></td>
        <td><h4>Debit</h4></td>
        <td><h4>Credit</h4></td>
        <td><h4>Amount Borrowed</h4></td>
    </tr>
    @foreach ($marginLoans as $row)
    <tr>
        <td>{{ date('Y-M-d', $row['mloan']['date'] )}}</td>
        <td>{{ $row['mloan']['description'] }}</td>
        <td>{{ $row['mloan']['debit'] }}</td>
        <td>{{ $row['mloan']['credit'] }}</td>
        <td>{{ $row['mloan']['borrowed'] }}</td>
    </tr>
    @endforeach
</table>
