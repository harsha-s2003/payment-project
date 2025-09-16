<?php if($studentfeeD->class==1) { 
$cl = $studentfeeD->class.'st';
} elseif ($studentfeeD->class==2) {
  $cl = $studentfeeD->class.'nd';
}
elseif ($studentfeeD->class==3) {
  $cl = $studentfeeD->class.'rd';
}
elseif ($studentfeeD->class==4 || $studentfeeD->class==5 || $studentfeeD->class==6 || $studentfeeD->class==7 || $studentfeeD->class==8 || $studentfeeD->class==9 || $studentfeeD->class==10) {
  $cl = $studentfeeD->class.'th';
}
$program = explode(',', $studentfeeD->program);
foreach ($program as $key => $programD) {
  $getData = $this->Common_model->GetData('student_program','',"program_name='".$programD."'",'','','','1');
 $pid[] =  $getData->id;
}
$tags = implode(', ', $pid);
$getPPData = $this->Common_model->GetData('student_program','',"id IN(".$tags.")",'','','','');
//print_r($getPPData);exit;
$ranNo = 'ADCC'.rand(11111,99999);
$TxnRefNo = $ranNo;
  ?>
<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 ">
                <div class="card p-3 border-0">

                    <h4 class="fw-lighter mb-3">Student Details</h5>
                        <div class="table-responsiven">

                            <table class="table table-bordered m-0">

                                <tr>
                                    <td width="180px">Student Name</td>
                                    <td><?= ucfirst($studentfeeD->name);?></td>
                                </tr>

                                <tr>

                                    <td>Student School Name</td>
                                    <td><?= ucfirst($studentfeeD->school);?></td>

                                </tr>

                                <tr>
                                    <td>Register Program Name </td>
                                    <td><?= ucfirst($studentfeeD->program);?></td>
                                </tr>

                                <tr>
                                    <td>Class</td>
                                    <td><?= ucfirst($cl);?></td>
                                </tr>

                                <tr>
                                    <td>Fees </td>
                                    <form method="POST" action="<?= site_url('order-pay');?>">
                                        <td>
                                            <?php foreach ($getPPData as $key => $getPPDataR) {  ?>
                                            <?php if($getPPDataR->program_fee_type=='S') { ?>
                                            <div>
                                                <input id="a<?= $getPPDataR->id?>" type="radio"
                                                    value="<?= $getPPDataR->program_name;?>" onchange="getFeeAmt();"
                                                    class="checkbox1" name="program">
                                                <label
                                                    for="a"><?= $getPPDataR->program_name;?>(₹<?= $getPPDataR->program_fee;?>)</label>
                                            </div>
                                            <?php } ?>
                                            <?php if($getPPDataR->program_fee_type=='I') { ?>
                                            <div style="margin-left: 20px;">
                                                <input type="radio" value="<?= $getPPDataR->program_name;?>"
                                                    class="checkbox1" onchange="getFeeAmt();" name="program">
                                                <label for="a"><?= $getPPDataR->program_name;?></label>
                                            </div>
                                            <div>
                                                <!-- <input type="checkbox" value="<?= $getPPDataR->install1;?>" class="checkbox1" onchange="getFeeAmt();" name="program[]">  -->
                                                <label for="b">First Installment(₹<?= $getPPDataR->install1;?>)</label>

                                            </div>
                                            <div>
                                                <!-- <input type="checkbox" value="<?= $getPPDataR->install2;?>" class="checkbox1" onchange="getFeeAmt();"> -->
                                                <label for="c">Second installment(₹<?= $getPPDataR->install2;?>)</label>
                                            </div>
                                            <?php } ?>
                                            <?php if($getPPDataR->program_fee_type=='N') { ?>
                                            <div>
                                                <input type="radio" value="<?= $getPPDataR->program_name;?>"
                                                    class="checkbox1" onchange="getFeeAmt();" name="program">
                                                <label for="a"><?= $getPPDataR->program_name;?></label>
                                                <!-- <input  type="text" value="" onchange="getFeeAmtI();" class="form-control col-md-3" placeholder="Enter Amount" style="width:200px;"> -->
                                            </div>
                                            <?php } ?>
                                            <?php } ?>
                                            <hr>
                                            <!-- <p class="mt-2 fw-semibold mb-0">₹ 2000  <span class=""> + GST ₹ 180</span></p> -->
                                            <div class="ftotal"></div>
                                            <!-- <form method="POST" action="<?= site_url('orderpay/pay');?>">


                                                <input type="hidden" name="TxnRefNo" value="<?= $TxnRefNo;?>"
                                                    required />


                                                <input type="text" name="Amount" required placeholder="Enter Amount"
                                                    style="width:250px;" />


                                                <input type="hidden" name="OrderInfo" value="Test Transaction" />
                                                <input type="hidden" name="Phone" value="<?= $studentfeeD->mobile?>" 

                                            <hr>
                                            <div>
                                                <button type="submit" class="btn btn-success">Pay Now
                                                    (Test)</button>
                                            </div>
                                    </form> -->

                                            <form method="POST" action="<?= site_url('order-pay'); ?>">
                                                <!-- Hidden Token -->
                                                <input type="hidden" id="atomToken" value="<?= $tokenId; ?>">

                                                <!-- Enter Amount -->
                                                <input type="text" name="amount" value="<?= $amount; ?>"
                                                    style="width:250px;" />

                                                <!-- Pay Now Button -->
                                                <button type="button" id="payBtn" class="btn btn-success">Pay
                                                    Now</button>
                                            </form>
                                            <!-- Mandatory JS CDN -->
                                            <script src="https://pgtest.atomtech.in/staticdata/ots/js/atomcheckout.js">
                                            </script>

                                            <script>
                                            document.getElementById("payBtn").addEventListener("click", function() {
                                                const tokenId = document.getElementById("atomToken").value;

                                                if (!tokenId) {
                                                    alert("Atom Token ID not generated!");
                                                    return;
                                                }

                                                const options = {
                                                    atomTokenId: tokenId,
                                                    merchId: "<?= MERCHANT_ID; ?>",
                                                    custEmail: "sagar.gopale@atomtech.in",
                                                    custMobile: "8976286911",
                                                    returnUrl: "<?= CALLBACK_URL; ?>"
                                                };

                                                new AtomPaynetz(options, 'uat');
                                            });
                                            </script>


                                        </td>
                                </tr>
                            </table>
                        </div>

                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
(function() {
    window.onpageshow = function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    };
})();
</script>