<?
include('include_path.inc.php');
require('/home/courtesy/public_html/siteadmin/includes/classes/MonthlyPaymentCalculator.php');

$calulate = new MonthlyPaymentCalculator;

$_CMS['DEBUG_MODE'] = true;
$_HDR['PRINT_VIEW'] = array_key_exists('print_view', $_REQUEST);

$id = $_REQUEST['id'];

$car = $p_search->getDetails($id);
$calulate->principal =$car['selling_price'];

$res = $SQLAuth->Query("SELECT * FROM calculator WHERE year = '" . $car['year'] . "'");

if (mysql_num_rows($res)) {
    $row = mysql_fetch_assoc($res);
    $calculatorData = $row;
} else {
    $res = $SQLAuth->Query("SELECT * FROM calculator WHERE year = '0'");
    $row = mysql_fetch_assoc($res);
    $calculatorData = $row;
}

// $_CMS['PROMAX_CALC_INTEREST_RATE']*
$calulate->interest = $calculatorData['interestRate']; 
// $_CMS['PROMAX_CALC_TERM']
$calulate->totalMonths = $calculatorData['term']; 
$calulate->getInterestPerMonth();
// $_CMS['PROMAX_CALC_DOWN_PAYMENT']
$calulate->getMonthlyPayment();

if ($_POST['action'] == 'getMonthlyPayment') {
    $downPayment = $_POST['down_payment'];

    $calulate->downPayment = $downPayment;


    echo json_encode(array('perMonth' => $calulate->getMonthlyPayment()));
    exit;
}

if (!array_key_exists('id', $_REQUEST)) {
    header("Location: inventory.php?e=" . __LINE__);
    exit;
}


if (!($car = $p_search->getDetails($id))) {
    header("Location: inventory.php?e=" . __LINE__);
    exit;
}
$_no_sidebar = false;
include('header.php');


if (!array_key_exists('showimgs', $_REQUEST)) {

}
//NOTE: something in header resets $car variable.  need to redeclare it after header.
?>
<div style="display: none">

</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#carousel").owlCarousel({
            itemsMobile: [479, 3],
            itemsTablet: [768, 4],
            autoPlay: true,
            stopOnHover: true,
            lazyLoad: true,
            scrollPerPage: true,
            responsiveClass:true,
            responsive:{
                0:{

                    dots:true
                },
                767:{

                    dots:false
                }
            }

        });
        $('.swipebox').swipebox({
            useCSS: true,
        });
    });
</script>
<style>
    .owl-item{
        width:135px!important;
    }
    #owl-demo .item{
        margin: 3px;
    }
    #owl-demo .item img{
        display: block;
        width: 100%;
        height: auto;
    }

    .large-car-image, .small-car-image{
        border-style:solid;
        border-width:.15em;
        border-radius:5px;
    }
    @media only screen and (max-width:767px) {
        .container{
            width:100%;
            padding-right:1.5em;
            padding-left:1.5em;
            margin:0;
        }

    }

</style>


<!-- similar vehicles -->
<style>
    .carSliderBox {
        margin:1em;
    }

    .carSliderBox .inventory-title, .inventory-title{
        margin-bottom: 0 !important;
    }
    p{
        line-height: 14px;
        margin-bottom:8px;
    }

    .estimate-btn{
        border:1px solid #A6A69E;
        background:#f2f2f2;
        width:16em;
        padding: 1em .5em .5em;
        height: 3.4em;
    }

    .estimate-btn p{
        font-weight: bolder;
    }

    .estimate-btn p.courtesy-orange{
        font-size: 3em;
        margin-bottom: 14px;
    }

    .estimate-text{
        color: #b4bcd1;
    }

    .text-center{text-align: center;}
    /**====================
    styles
    =======================*/
    .courtesy-orange{color:#e15a27;}
    .courtesy-blue{color: #4a629d;}

    .btn-secondary {
        background: #e15a27;
        border: 1px solid #e15a27;
        color: #fff;
        margin-top: 1em;
    }

    .availablilty h5{
        color:#a6a69e !important;
    }



    @media only screen and (max-width:767px) {
        .align-center{
            text-align: center;
            text-align:-webkit-center;
            margin: 0 auto;
        }
        .align-center h2{
            text-align: center !important;
            text-align:-webkit-center !important;
            margin: 0 auto;
        }
        .estimate-btn{
            margin: 0 auto;
        }
    }

    @media only screen and (max-width:1200px){
        .estimate-btn{
            width:unset;
        }
    }

</style>


<div class="grid_9">

    <div id="inventoryContentContainer">
        <h2 class="car-name"><?php echo $car['year'] . ' ' . $car['make'] . ' ' . $car['model'] . ' ' . $car['style']; ?></h2>
        <?php /* ?><h1 class="has-background car-inventory">Car Inventory</h1><?php */ ?>
        <div class="row">
            <div class="grid_3">
                <a href="<?php echo $_CMS['SITE_URL']; ?>/inventory.php?new_used=<?php echo $car['new_used']; ?>" class="car-backtoresults-btn"><i
                        class="fa fa-arrow-left">&nbsp;Return to Results</i></a></td>
            </div>
            <div class="grid_3">
                <?php
                if (!$_HDR['PRINT_VIEW']) {
                    ?><a href="<?php echo $_CMS['SITE_URL']; ?>/inventory_details.php?id=<?php echo $id; ?>&print_view"  class="car-printpage-btn"><i class="fa fa-print">
                            &nbsp;Print Page</i></a>
                    <?php
                } else {
                    ?><a href="<?php echo $_CMS['SITE_URL']; ?>/inventory_details.php?id=<?php echo $id; ?>" class="car-noprintpage-btn"><i class="fa fa-desktop">&nbsp;Normal
                            View</i></a>
                    <?php
                }
                ?>
            </div>
            <div class="grid_3">
                <a href="mailto:?subject=<?php echo $_CMS['SITE_NAME'] . ' - Someone Thinks You Might Be Interested In A Vehicle'; ?>&body=<?php echo urlencode($_CMS['SITE_URL'] . '/inventory_details.php?id=' . $id) . '%0A%0A'; ?>"
                   class="car-emailtofriend-btn"><i class="fa fa-envelope">&nbsp;Email to Friend</i></a>
            </div>
        </div>

        <div>
            <?php
            if ($car['images'] !== false && is_array($car['images'])) {
                ?>
                <div>
                    <a href="<?php echo $_CMS['SITE_URL']; ?>/images/bin/<?php echo $car['images'][0]['large_id']; ?>.jpg">
                        <img class="img-responsive large-car-image" src="<?php echo $_CMS['SITE_URL']; ?>/images/bin/<?php echo $car['images'][0]['medium_id']; ?>.jpg"
                             onerror="this.src='<?= $site_url ?>/images/car-inventory-no-image.jpg'"/>
                        <?
                        if ($car['special_info'] != 'Normal') {
                            ?>
                            <span class="car-<?php echo str_replace(' ', '', $car['special_info']); ?>">&nbsp;</span>
                            <?
                        }
                        ?>
                    </a>
                </div>

                <div class="row" style="margin-left:0;">
                    <?php
                    if (count($car['images']) > 1)
                    {
                    ?>
                    <div id="carousel"> <!--id="car-imageThumbnails-->
                        <?php
                        $loop = 0;
                        $looped_once = false;
                        $initial_max_display = 99;
                        foreach ($car['images'] as $car_image) {
                            if (!$looped_once) {
                                $looped_once = true;
                                continue;
                            }
                            $res = $SQLAuth->Query("SELECT file_name FROM cms_file_uploads WHERE file_id = '" . $car_image['micro_id'] . "'");
                            list($file_name) = mysql_fetch_row($res);
                            //list($width, $height, $type, $attr) = getimagesize($_CMS['FILE_UPLOADS_PATH'].'/'.$file_name);
                            $loop++;
                            ?>
                            <a class="swipebox" href="<?php echo $_CMS['SITE_URL']; ?>/images/bin/<?php echo $car_image['large_id']; ?>.jpg">
                                <img class="small-car-image" src="<?php echo $_CMS['SITE_URL']; ?>/images/bin/<?php echo $car_image['micro_id']; ?>.jpg" border="0"/>
                            </a> <!-- rel="gallery1" -->
                            <?php

                        }
                        }
                        ?>
                        <div style="clear:both;"></div>
                    </div>
                </div>

                <?php
                if ($loop > $initial_max_display && !array_key_exists('showimgs', $_REQUEST)) {
                    ?>
                    <a class="showImgs" href="<?php echo $_CMS['SITE_URL']; ?>/inventory_details.php?id=517&showimgs">Click here to view more images</a>
                    <?
                }
            } else {
                ?>
                <div id="car-noImageLarge">
                    <?
                    if ($_HDR['PRINT_VIEW']) {
                        ?>
                        <img src="<?php echo $_CMS['SITE_URL']; ?>/images/car-inventory-no-image.jpg" width="399" height="300"/>
                        <?
                    } else {
                        ?>
                        <img class="no-img-dets" src="<?= $_CMS['SITE_URL'] ?>/images/car-inventory-no-image.jpg">
                        <?
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div>

        <script type="text/javascript">
            function outputUpdate(val) {
                document.querySelector('#down_payment').value = val;
            }

            $(document).ready(function(e){
                $('#submit_down_payment').on('click', function(){
                    alert( $('#down_payment').val() );
                    // $("#div_element").load('script.php'); reload my div
                });
            });

            function getPerMonth (e) {
                var downPayment = e.target.value;

                var postData = { action: 'getMonthlyPayment', down_payment: downPayment };

                jQuery.ajax({
                    method: 'POST',
                    data: postData,
                    success: function (resp) {
                        var json = JSON.parse(resp);

                        document.getElementById('monthly-payment').innerHTML = json.perMonth;
                    }
                });
            }
        </script>
  
    
        <?php $current_page = "http://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ; ?>
        <div id="car-detailsContainerDiv">
            <div class="row">
                <div class="col-xs-12 text-center">
                
                    <h2>Down Payment</h2>
                    <label for="scale">0</label>
                    <input type="range" min="0" max="<?=$car['selling_price']?>" value="50" id="scale" step="1" onchange="getPerMonth(event)" oninput="outputUpdate(value)">
                    <output for="scale" id="down_payment">0</output>
                
                </div>
            </div>
            <div class="half-span">
                <?php
                if (!empty($car['selling_price']) && $car['selling_price'] > 0) {
                    ?>
                    <strong class="label">Price: </strong><strong class="details-highlight">$<?php echo number_format($car['selling_price'], 0, '.', ','); ?></strong>
                    <?
                }
                ?>
            </div>

            <div class="half-span">
                <?php
                if ($calulate->getMonthlyPayment() > 0) {
                    ?>
                    <strong class="label">per/mo: </strong><strong id="monthly-payment" class="details-highlight">$<?php echo $calulate->getMonthlyPayment(); ?></strong>
                    <?
                }
                ?>
            </div>
            <div class="clear">
                <?php
                if (!empty($car['mileage']) && $car['mileage'] > 0) {
                    ?>
                    <strong class="label">Miles: </strong><strong class="details-highlight"><?php echo number_format($car['miles']); ?></strong>
                    <?
                }
                ?>
            </div>


            <div class="row text-center">
                <div class="grid_3">
                    <a href="<?php echo $_CMS['SITE_URL']; ?>/request_a_quote.php?id=<?php echo $id; ?>" class="btn-main">Request More Info</a>
                </div>
                <div class="grid_3">
                    <a href="<?php echo $_CMS['SITE_URL']; ?>/schedule_test_drive.php?id=<?php echo $id; ?>" class="btn-main">Schedule Test Drive</a>
                </div>
                <div class="grid_3">
                    <div class="btn-main" style="cursor:pointer;"
                         onclick="window.open('https://extranet.dealercentric.com/app-templates/LoanApplication/QuickApplication.aspx?AssociateID=3992&AssociateTypeID=4000&htm=1', 'mywindow','location=1,status=1,scrollbars=1,width=865,height=700');">
                        Get Pre-Approved Here!
                    </div>
                    <?php /* ?><img id="#preapproved"class="img-responsive" src="<?=$site_url?>/images/Get Pre-Approved Banner.png" border="0" style="cursor:pointer; max-height: 39px;" onclick="window.open('https://extranet.dealercentric.com/app-templates/LoanApplication/QuickApplication.aspx?AssociateID=3992&AssociateTypeID=4000&htm=1', 'mywindow','location=1,status=1,scrollbars=1,width=865,height=700');" /><?php */ ?>
                </div>
            </div>
            <?php /*?>
    <div style="border:1px solid #D3D3D3; background-color:#F1F1F1;margin:0px;padding:20px;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center"><img src="images/inventory_walk_away.gif" border="0" width="467" height="46" alt="" /></td>
      </tr>
    </table>
    </div>
<?php */ ?>
            <?php if (!empty($car['description'])) { ?>

                <strong class="vDetails-hdr">Seller Notes</strong>
                <div class="seller-notes">
                    <p><?php echo $car['description'] ?></p>
                </div>
            <?php } ?>
            <strong class="vDetails-hdr">Vehicle Details</strong>
            <table width="100%" cellpadding="3" cellspacing="0">
                <tr>
                    <th scope="col">Year:</th>
                    <td><?php echo $car['year'] > 0 ? $car['year'] : '---'; ?></td>
                    <th scope="col">Miles:</th>
                    <td><?php echo $car['miles'] > 0 ? number_format($car['miles']) : '---'; ?></td>
                </tr>
                <tr>
                    <th scope="col">Make:</th>
                    <td><?php echo !empty($car['make']) ? $car['make'] : '---'; ?></td>
                    <th scope="col">Vin #:</th>
                    <td><?php echo !empty($car['vehicleid']) ? $car['vehicleid'] : '---'; ?></td>
                </tr>
                <tr>
                    <th scope="col">Model:</th>
                    <td><?php echo !empty($car['model']) ? $car['model'] : '---'; ?></td>
                    <th scope="col">Stock #:</th>
                    <td><?php echo !empty($car['stock_number']) ? $car['stock_number'] : '---'; ?></td>
                </tr>
                <tr>
                    <th scope="col">Style:</th>
                    <td><?php echo !empty($car['body']) ? $car['body'] : '---'; ?></td>
                    <th scope="col">Color:</th>
                    <td><?php echo !empty($car['exterior_color']) ? $car['exterior_color'] : '---'; ?></td>
                </tr>
            </table>
            <?
            if (!empty($car['options'])) {
                $options = explode(',', $car['options']);

                ?>
                <strong class="vDetails-hdr">Features</strong>
                <div style="padding:0 20px 20px;">
                    <table width="100%" cellpadding="3" cellspacing="0">
                        <tr>
                            <?php
                            $loop = 0;
                            foreach ($options as $eq) {
                                $loop++;
                                ?>
                                <td><?php echo $eq; ?></td>
                                <?
                                if ($loop % 3 == 0) {
                                    echo '</tr><tr>';
                                }
                            }
                            ?>
                        </tr>
                    </table>
                </div>
                <?
            }
            ?>

            <style>
                .simliar-car-img{
                    margin-top:10px;
                }
                .car-details-text{
                    font-size: 12px;
                }
                .courtesy-orange a, .courtesy-orange p{
                    font-size: 3em;
                }
                .courtesy-blue{
                    font-size:12px;
                }

                .details-estimate-btn{
                    border:1px solid #A6A69E;
                    background:#f2f2f2;
                    /*   width:16em;*/
                    font-weight:bolder;
                    padding: 10px 5px 5px;
                    height: 43px;
                }

                .carImage {
                    max-height:152px;
                }

                .courtesy-principal{
                    color: #eb8d6b;
                }

                @media only screen and (min-width:767px) and (max-width:1024px) {
                    .details-estimate-btn {
                        font-size: 12px;
                    }
                }

                @media only screen and (max-width:767px) {
                    .align-center{
                        text-align: center;
                        text-align:-webkit-center;
                        margin: 0 auto;
                    }
                    .align-center h2{
                        text-align: center !important;
                        text-align:-webkit-center !important;
                        margin: 0 auto;
                    }

                }

                @media only screen and (max-width:1024px){
                    .details-estimate-btn{
                        margin: 0 auto;
                    }

                    .mobile-center {
                        margin: 0 auto;
                        text-align: center;
                    }
                    .carImage {
                        max-height: 313px;
                    }
                }
                /*
                @media only screen and (max-width:1200px){
                    .details-estimate-btn{
                        width:unset;
                    }
                }

                @media (max-width: 1024px) {
                    [class*="grid_"] {
                        float: none;
                        display: block;
                        width: 100%;
                        margin-left: 0;
                        -webkit-box-sizing: border-box;
                        -moz-box-sizing: border-box;
                        box-sizing: border-box;
                    }

                }

                @media (max-width: 1199px) and (min-width: 768px){
                    .row {
                        margin-left: 0;
                    }
                */
                }
            </style>

            <?

            if (!empty($car['sticker'])) {
                ?>
                <strong class="vDetails-hdr">Dealer Comments</strong>
                <?php
                echo '<p style="margin:10px 20px;">' . $car['sticker'] . '</p>';
            }
            ?>

            <strong class="vDetails-hdr">Similar Vehicles</strong>
            <?php
            $myCounter = 1;
            $similar = $p_search->getSimilar($id);
            $even = false;
            foreach ($similar as $s_car) {

                $res = $SQLAuth->Query("SELECT * FROM calculator WHERE year = '" . $s_car['year'] . "'");

                if (mysql_num_rows($res)) {
                    $row = mysql_fetch_assoc($res);
                    $calculatorData = $row;
                } else {
                    $res = $SQLAuth->Query("SELECT * FROM calculator WHERE year = '0'");
                    $row = mysql_fetch_assoc($res);
                    $calculatorData = $row;
                }


                $calulate->interest = $calculatorData['interestRate']; /*$_CMS['PROMAX_CALC_INTEREST_RATE']*/ 
                $calulate->totalMonths = $calculatorData['term']; /*$_CMS['PROMAX_CALC_TERM']*/
                $calulate->getInterestPerMonth();
                $calulate->downPayment = $calculatorData['downPayment'];/*$_CMS['PROMAX_CALC_DOWN_PAYMENT']*/
                $calulate->getMonthlyPayment();

                $img = $p_search->getImages($s_car['car_id'], 1);

                ?>
                <div class="row  <?= (($myCounter % 2 == 0) ? 'car-even' : 'car-odd') ?>"><?php $myCounter++; ?>
                    <div class="grid_3">
                        <a href="<?php echo $_CMS['SITE_URL']; ?>/inventory_details.php?id=<?php echo $s_car['car_id']; ?>">
                            <img class="img-responsive carImage center-x-xs center-x-sm simliar-car-img"
                                 src="<?php echo $_CMS['SITE_URL']; ?>/images/<?php echo ($img !== false) ? 'bin/' . $img['large_id'] . '.jpg' : 'car-inventory-no-image.jpg'; ?>"
                                 onerror="this.src='<?= $site_url ?>/images/car-inventory-no-image.jpg'">
                            <!-- removed special info -->
                        </a>
                    </div><!-- grid 4 -->
                    <div class="grid_6">
                        <div class="row">


                            <!-- <div class="row"> -->

                            <div class="grid_3 mobile-center"> <!-- align-center text-center -->
                                <?
                                // Replace occurrences of Wagon with SUV
                                $s_car['style'] = str_replace('Wagon', 'SUV', $s_car['style']);
                                ?>

                                <a class="" href="<?php echo $_CMS['SITE_URL']; ?>/inventory_details.php?id=<?php echo $s_car['car_id']; ?>"> <!-- text-center align-center -->
                                    <h2 class="inventory-title "><?php echo $s_car['year'] . ' ' . $s_car['make'] . ' ' . $s_car['model'] . ' ' . $s_car['style']; ?></h2></a> <!-- align-center -->
                                <?
                                if ($s_car['selling_price'] == 'Yes' && is_numeric($s_car['selling_price']) && $s_car['selling_price'] > 0 || $s_car['new_used'] == 'N' && is_numeric($s_car['selling_price']) && $s_car['selling_price'] > 0) {
                                    ?>
                                    <a href="<?php echo $_CMS['SITE_URL']; ?>/inventory_details.php?id=<?php echo $s_car['car_id']; ?>"><h3 class="price">
                                            $<?php echo number_format($s_car['selling_price']); ?></h3></a><br>
                                    <?
                                }
                                ?>

                                <p class="car-details-text">
                                    <?php if (! empty($s_car['miles'])){ ?>
                                        Miles: <?=$s_car['miles'] ?> miles
                                    <?php } else { ?>
                                        Miles: 0 miles
                                    <?php } if ($s_car['exterior_color']) { ?>
                                        <br>Color: <?=$s_car['exterior_color']?>
                                    <?php } ?>
                                    <br>
                                    <?php if (! empty($s_car['stock_number'])){ ?>
                                        Stock #: <?=$s_car['stock_number'] ?>
                                    <?php } else { ?>
                                        Stock #: Call for more info
                                    <?php } ?>
                                    <br>
                                    <?php if (! empty($s_car['body'])){ ?>
                                        Style: <?=$s_car['body'] ?>
                                    <?php } else { ?>
                                        Style: Call for more info
                                    <?php } ?>
                                    <!--transmission removed -->
                                </p>
                                <!-- Estimate button -->
                                <div class="details-estimate-btn">
                                    <p class="courtesy-orange">
                                        <?php ?>
                                        <?php $calulate-> principal = $s_car['selling_price']; ?>
                                        <?php if($calulate->getMonthlyPayment() > 0){
                                            echo "$" . $calulate->getMonthlyPayment() . "<span style='font-size:12px;'> per/mo <span class='estimate-text'>**Est. Payment</span></span>";
                                        } else {
                                            echo "<span style='font-size:12px;'>Call (859)272-8900 For An Estimate</span>";
                                        } ?>
                                    </p>

                                    <p class="courtesy-principal">
                                        <?php if (! empty($s_car['selling_price'])) { ?>
                                            <?php $price = $s_car['selling_price']  == 0 ? 'Call for Pricing' : '$'.number_format($s_car['selling_price']); ?>
                                            <a href="<?php echo $_CMS['SITE_URL']; ?>/inventory_details.php?id=<?php echo $s_car['car_id']; ?>"><?php echo $price ?></a><br>
                                        <?php } else { ?>
                                            <span style='font-size:12px;'><em>Call (859) 272-8900 For Pricing</em></span>
                                        <?php } ?>

                                    </p>
                                </div>


                            </div>

                            <div class="grid_3">
                                <a href="https://extranet.dealercentric.com/app-templates/LoanApplication/QuickApplication.aspx?AssociateID=3992&AssociateTypeID=4000&htm=1" target="_blank" class="btn-secondary is-inventory center-x"><span>Get Pre-Approved</span></a>
                                <a href="<?php echo $_CMS['SITE_URL']; ?>/inventory_details.php?id=<?php echo $s_car['car_id']; ?>" class="btn-main is-inventory top-marg center-x"><span>View Details</span></a>
                                <div class="text-center availablilty">
                                    <h5>Call For Availablilty</h5>
                                    <h5>859.272.8900</h5>
                                </div>
                            </div>

                            <!-- </div> -->
                        </div> <!-- row -->
                    </div>  <!-- grid_6 -->
                </div>


                <?/*<tr class="similar-car-<?php echo $even ? 'even' : 'odd'; ?>"onMouseOver="jQuery(this).removeClass('similar-car-<?php echo $even ? 'even' : 'odd'; ?>').addClass('similar-car-hover');" onMouseOut="jQuery(this).addClass('similar-car-<?php echo $even ? 'even' : 'odd'; ?>').removeClass('similar-car-hover');">
            <td valign="top" width="128"><a class="car-listimage" href="<?php echo $_CMS['SITE_URL']; ?>/inventory_details.php?id=<?php echo $s_car['car_id']; ?>" style="background-image:url(<?php echo $_CMS['SITE_URL']; ?>/images/<?php echo ($img !== false) ? 'bin/'.$img['micro_id'].'.jpg' : 'car-inventory-no-image.gif'; ?>);<?php echo ($img !== false) ? '' : ' border:none;'; ?>"><?php
                if($img != false && $_HDR['PRINT_VIEW'])
                {
                    ?><img src="<?php echo $_CMS['SITE_URL']; ?>/images/bin/<?php echo $img['micro_id']; ?>.jpg" border="0" /><?
                }
                if($s_car['special_info'] != 'Normal')
                {
                    ?><span class="car-<?php echo str_replace(' ', '', $s_car['special_info']); ?>">&nbsp;</span><?
                }
                ?></a></td>
            <td valign="top">
                <?
                    // Replace occurrences of Wagon with SUV
                    $s_car['style'] = str_replace('Wagon', 'SUV', $s_car['style']);
                ?>
                <h3><a href="<?php echo $_CMS['SITE_URL']; ?>/inventory_details.php?id=<?php echo $s_car['car_id']; ?>"><?php echo $s_car['year'].' '.$s_car['make'].' '.$s_car['model'].' '.$s_car['style']; ?></a></h3>
                <strong>Stock #: </strong><?php echo $s_car['stock_number']; ?><br />
                <strong>Exterior Color: </strong><?php echo ucwords(strtolower($s_car['color'])); ?><br />
                <?php
                if(is_numeric($s_car['mileage']))
                {
                    echo '<strong>Miles: </strong>'. number_format($s_car['mileage'], 0, '.', ',').'<br />';
                }
                if($s_car['selling_price'] == 'Yes' && is_numeric($s_car['selling_price']) && $s_car['selling_price'] > 0  || $car['new_used'] == 'N' && is_numeric($s_car['selling_price']) && $s_car['selling_price'] > 0 )
                {
                    echo '<strong>Price </strong>$'.number_format($s_car['selling_price'], 0, '.', ',');
                }
                ?>
            </td>
        </tr>*/
                ?>
                <?
                $even = !$even;
            }
            ?>
            <br />

            <div class="grid_8 text-center"><a href="http://reviewequity.com/?q=er36448" target="_blank"><img
                        src="http://www.courtesyonwheels.com/images/COWEquityBanners/69_General_1_Yes_Banner728x90.jpg" class="img-responsive" style="max-width: 270px;"/></a>
            </div>


            <div style="clear:both;"></div>
            <br/>
            <div style="border:1px solid #D3D3D3; background-color:#F1F1F1;margin:0px;padding:20px;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="75" align="center">
                            <img src="images/bbbseal.gif" border="0" align="left" width="51" height="79" alt="Better Business Bureau" style="padding-right:10px;"/>
                        </td>
                        <td class="copyDark">Courtesy Acura is an accredited Better Business Bureau memeber since 1988. We have a large selection of certified pre-owned
                            vehicles to choose from and strive to make your buying experience hassle free. We offer financing for all customers so call toll free (866)
                            737-3764.
                        </td>
                    </tr>
                </table>
            </div>

            <br/><br/>
            <div class="price-disclaimer text-center" style="font-size: .75em; border: 1px solid #D3D3D3; border-radius: 5px;">
                Price excludes tax, title and tags. Price assumes that final purchase will be made in the State of KY, unless vehicle is non-transferable. Vehicle subject to prior sale. Applicable transfer fees are due in advance of vehicle delivery and are separate from sales transactions.
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>

</div>


<?php
include('footer.php');
?>