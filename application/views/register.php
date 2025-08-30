<section>
    <div class="container">
        <div class="row">

            <div class="col-lg-7 order-2 order-md-1">
                <figure class="login-img">
                    <img src="<?= base_url();?>assets/img/Login-rafiki.svg" alt="">
                </figure>
            </div>
            <div class="col-lg-5 order-1 order-md-2">

                <h3 class="text-center mb-5"> <i class="bi bi-card-list fs-4"></i> Registration Form</h3>
                <form class="form" method="POST" action="<?= site_url('save-registration-data');?>">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="name" placeholder="Student Name" name="name"
                            required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="contact" placeholder="Contact / Mobile No"
                            name="mobile" required pattern="[7-9]{1}[0-9]{9}" maxlength="10">
                    </div>
                    <div class="mb-3">
                        <select name="school" id="schoolname" class="form-control" required>
                            <option value="">Select School</option>
                            <?php foreach($getSchool as $getSchoolRow) { ?>
                            <option value="<?= $getSchoolRow->school_name;?>"><?= $getSchoolRow->school_name; ?>
                            </option>
                            <?php } ?>

                        </select>

                    </div>
                    <div class="mb-3">
                        <select name="class" id="Integrated" class="form-control" required
                            onchange="getProgram(this.value);">
                            <option value="">Class</option>
                            <?php foreach($getclass as $getclassR) { ?>
                            <option value="<?= $getclassR->class;?>"><?= $getclassR->class;?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <!--  <div class="mb-3">
            <select name="program_name" class="form-control SelectProgram" required> 
              <option value="">Select Program</option>
                        
            </select>
            
          </div> -->
                    <!-- Program Multi Select Dropdown -->
                    <div class="mb-3">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle form-control" type="button"
                                id="multiSelectDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                style="background-color: white;">
                                Select Program
                            </button>
                            <ul class="dropdown-menu SelectProgram form-control text-center"
                                aria-labelledby="multiSelectDropdown">

                                <?php foreach($getProgram as $programRow) { ?>
                                <li>
                                    <label>
                                        <input type="checkbox" name="program_name[]"
                                            value="<?= $programRow->program_name; ?>">
                                        <?= $programRow->program_name; ?>
                                    </label>
                                </li>
                                <?php } ?>

                            </ul>
                        </div>
                    </div>
                    <div class="mb-3">
                        <select name="academic_sess" id="Session " class="form-control" required>
                            <option value="">Academic Session</option>
                            <!-- <option value="">2023-25</option> -->
                            <option value="2024-25">2024-2025</option>
                            <option value="2025-26">2025-2026</option>

                        </select>

                    </div>



                    <button type="submit" class="btn btn-primary w-100" onclick="getPrg();">Register</button>
                </form>

            </div>
        </div>
    </div>
</section>
<script>
// Ajax call for programs
function getProgram(clid) {
    $.ajax({
        type: 'POST',
        url: "<?= site_url('getProgramData');?>",
        data: {
            class: clid
        },
        success: function(resultData) {
            $(".SelectProgram").empty().append(resultData);
        }
    });
}

// Dropdown button text update
$(document).on("change", ".SelectProgram input[type=checkbox]", function() {
    let selected = [];
    $(".SelectProgram input[type=checkbox]:checked").each(function() {
        selected.push($(this).val());
    });
    $("#multiSelectDropdown").text(selected.length > 0 ? selected.join(", ") : "Select Program");
});

// Validation
function getPrg() {
    var name = $('#name').val();
    var contact = $('#contact').val();
    var schoolname = $('#schoolname').val();
    var Integrated = $('#Integrated').val();
    var selectedPrograms = $(".SelectProgram input[type=checkbox]:checked");

    if (name == "") {
        alert('please enter name');
        return false;
    }
    if (contact == "") {
        alert('please enter mobile no');
        return false;
    }
    if (schoolname == "") {
        alert('please Select school');
        return false;
    }
    if (Integrated == "") {
        alert('please Select class');
        return false;
    }
    if (selectedPrograms.length === 0) {
        alert('please Select Program');
        return false;
    }

    return true; // ✅ sab sahi hai, form submit hoga
}
</script>