<!--Created by PhpStorm.-->
<!--User: rikazdev-->
<!--Date: 3/8/15-->
<!--Time: 6:05 AM-->

<!doctype html>
<html>
<head>
    <!--    <link rel="stylesheet" type="text/css" media="screen" href="/app/styles/bootstrap.min.css">-->

    <!--    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">-->

</head>

<body>
<div>
    <div align="center"><h2> Portfolio Summary </h2></div>
    <div>
        <table style="width: 800px">
            <tr>
                <td style="text-align: center"><h5><b>PURCHASE AND SALES</b></h5></td>
                <td style="width: 30px"></td>
                <td><h5><b>Inception Date: {{ $inceptionDate }} </b></h5></td>
            </tr>
            <tr>
                <td style="text-align: right">Funds used to purchase shares that have been sold:</td>
                <td style="width: 30px"></td>
                <td style="text-align: left">${{ $purchaseFund }}</td>
            </tr>
            <tr>
                <td style="text-align: right">Proceeds of all sales to date:</td>
                <td style="width: 30px"></td>
                <td style="text-align: left">${{ $totalSales }}</td>
            </tr>
            <tr>
                <td style="text-align: right">Profit or Loss on sales:</td>
                <td style="width: 30px"></td>
                <td style="text-align: left">${{ $profitOrLoss }}</td>
            </tr>
        </table>

        <table style="width: 800px">
            <tr>
                <td style="text-align: center"><h5><b>CURRENT SITUATION</b></h5></td>
                <td style="width: 30px"></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: right">Total purchases including income re-invested:</td>
                <td style="width: 30px"></td>
                <td style="text-align: left">${{ $totalPurchasesWithReinvestment }}</td>
            </tr>
            <tr>
                <td style="text-align: right">Current value of the shares:</td>
                <td style="width: 30px"></td>
                <td style="text-align: left">${{ $currentValueOfShares }}</td>
            </tr>
            <tr>
                <td style="text-align: right">Cash holdings in the account:</td>
                <td style="width: 30px"></td>
                <td style="text-align: left">${{ $cashHoldingInTheAccount }}</td>
            </tr>
            <tr>
                <td style="text-align: right">Total portfolio value:</td>
                <td style="width: 30px"></td>
                <td style="text-align: left">${{ $totalPortfolioValue }}</td>
            </tr>
        </table>

        <table style="width: 800px">
            <tr>
                <td style="text-align: center"><h5><b>PERFORMANCE</b></h5></td>
                <td style="width: 30px"></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: right">Total profit or loss to date:</td>
                <td style="width: 30px"></td>
                <td style="text-align: left">${{ $totalProfitOrLossToDate }}</td>
            </tr>
            <tr>
                <td style="text-align: right">Capital profit or loss in the current portfolio:</td>
                <td style="width: 30px"></td>
                <td style="text-align: left">${{ $capitalProfitOrLoss }}</td>
            </tr>
            <tr>
                <td style="text-align: right">Current portfolio % change since inception:</td>
                <td style="width: 30px"></td>
                <td style="text-align: left">${{ $currentPortfolioChange }}</td>
            </tr>

        </table>

        <table style="width: 800px">
            <tr>
                <td style="text-align: center"><h5><b>MARGIN LOAN</b></h5></td>
                <td style=""></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: right"><b>Amount Approved:</b></td>
                <td style="text-align: left">${{ $amountApproved }}</td>
                <td style="text-align: left"><b>Owners Equity:</b></td>
                <td style="text-align: left">${{ $ownersEquity }}</td>
            </tr>
            <tr>
                <td style="text-align: right"><b>Amount Drawn:</b></td>
                <td style="text-align: left">${{ $amountDrawn }}</td>
                <td style="text-align: left"><b>Interest Rate:</b></td>
                <td style="text-align: left">${{ $interestRate }}</td>
            </tr>
            <tr>
                <td style="text-align: right"></td>
                <td style="text-align: left"></td>
                <td style="text-align: left"><b>LVR:</b></td>
                <td style="text-align: left">${{ $lvr }}</td>
            </tr>

        </table>

        <table style="width: 800px">
            <tr>
                <td style="text-align: left"><h5><b>YIELD (income stream)</b></h5></td>
                <td style=""></td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: left">Current net dividend yield based on funds used:</td>
                <td style="text-align: left">${{ $netDividentYield }}</td>
                <td style="text-align: left"> {{ $netDividentYieldPercentage }}%</td>
            </tr>
            <tr>
                <td style="text-align: left">Current gross dividend yield based on funds used:</td>
                <td style="text-align: left">${{ $grossDividentYield }}</td>
                <td style="text-align: left">{{ $grossDividentYieldPercentage }}%</td>
            </tr>


            <tr>
                <td style="text-align: left">Current net dividend yield if purchased at the current price:</td>
                <td style="text-align: left"></td>
                <td style="text-align: left"> {{ $currentNetDevidentYieldForCurrentPrice }}%</td>
            </tr>
            <tr>
                <td style="text-align: left">Current gross dividend yield if purchased at the current price:</td>
                <td style="text-align: left"></td>
                <td style="text-align: left"> {{ $currentGrossDevidentYieldForCurrentPrice }}%</td>
            </tr>
            <tr>
                <td style="text-align: left">This is an increase in the gross income due to the increased yield per year
                    of:
                </td>
                <td style="text-align: left"></td>
                <td style="text-align: left"> ${{ $increaseInGrossIncome }}</td>
            </tr>
            <tr>
                <td style="text-align: left"> The dividend yield has increased since inception by:</td>
                <td style="text-align: left"></td>
                <td style="text-align: left"> {{ $incresedDevidentYield }}%</td>
            </tr>
            <tr>
                <td style="text-align: left">Estimated annual dividend income stream (excluding tax credits)</td>
                <td style="text-align: left"></td>
                <td style="text-align: left"> ${{ $estimatedAnualDevidentIncome }}</td>
            </tr>
            <tr>
                <td style="text-align: left">Estimated Taxation credits in addition to dividends:</td>
                <td style="text-align: left"></td>
                <td style="text-align: left"> ${{ $estinatedTaxationCredits }}</td>
            </tr>
            <tr>
                <td style="text-align: left">Average extent of fully franked imputation credits:</td>
                <td style="text-align: left"></td>
                <td style="text-align: left"> {{ $averageImputationCredits }}%</td>
            </tr>
        </table>
    </div>


</div>

<div>

    <?php echo $childSales; ?>

</div>
<div>

    <? echo $childCurrentPortfolio; ?>

</div>
<div>

    <? echo $childMarginLoan; ?>

</div>
<div>

    <? echo $childIndexWeightings; ?>

</div>
<div>

    <? echo $childPortfolioWeightings; ?>

</div>

</div>


</body>
</html>
