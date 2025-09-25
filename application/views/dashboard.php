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
                                    <td>
                                        <?php foreach ($getPPData as $key => $getPPDataR) {  ?>
                                        <?php if($getPPDataR->program_fee_type=='S') { ?>
                                        <div>
                                            <input id="a<?= $getPPDataR->id?>" type="radio"
                                                value="<?= $getPPDataR->program_name;?>" onchange="getFeeAmt();"
                                                class="checkbox1" name="program">
                                            <label for="a">
                                                <?= $getPPDataR->program_name;?> (₹<?= $getPPDataR->program_fee;?>)
                                            </label>
                                        </div>
                                        <?php } ?>

                                        <?php if($getPPDataR->program_fee_type=='I') { ?>
                                        <div style="margin-left: 20px;">
                                            <input type="radio" value="<?= $getPPDataR->program_name;?>"
                                                class="checkbox1" onchange="getFeeAmt();" name="program">
                                            <label><?= $getPPDataR->program_name;?></label>
                                        </div>
                                        <div>
                                            <label>First Installment (₹<?= $getPPDataR->install1;?>)</label>
                                        </div>
                                        <div>
                                            <label>Second Installment (₹<?= $getPPDataR->install2;?>)</label>
                                        </div>
                                        <?php } ?>

                                        <?php if($getPPDataR->program_fee_type=='N') { ?>
                                        <div>
                                            <input type="radio" value="<?= $getPPDataR->program_name;?>"
                                                class="checkbox1" onchange="getFeeAmt();" name="program">
                                            <label><?= $getPPDataR->program_name;?></label>
                                        </div>
                                        <?php } ?>
                                        <?php } ?>

                                        <hr>
                                        <div class="ftotal"></div>

                                        <!-- ✅ Payment Form (separate, no nesting) -->
                                        <form id="paymentForm" method="POST">
                                            <!-- Hidden Token -->
                                            <input type="hidden" id="atomToken"
                                                value="<?= isset($atomTokenId) ? $atomTokenId : ''; ?>">

                                            <!-- Amount -->
                                            <input type="text" name="amount"
                                                value="<?= isset($amount) ? $amount : '0.00'; ?>"
                                                style="width:250px;" />

                                            <!-- Pay Now button -->
                                            <a class="btn btn-success" href="javascript:openPay()" role="button">
                                                Pay Now
                                            </a>
                                        </form>
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
<script src="https://pgtest.atomtech.in/staticdata/ots/js/atomcheckout.js"></script>
<script>
function openPay() {
    const options = {
        "atomTokenId": document.getElementById("atomToken").value,
        "merchId": "446442",
        "custEmail": "sagar.gopale@atomtech.in",
        "custMobile": "8976286911",
        "returnUrl": "<?= base_url('welcome/response') ?>"
    };
    let atom = new AtomPaynetz(options, 'uat'); // 'uat' for testing
}
</script>