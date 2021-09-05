
function ISNullData(Name) {
    if ($("form [name='" + Name + "']").val() == "") {
        $("form [name='" + Name + "']").addClass("is-invalid");
        NullData++;
    }
}


function validEmail(Name) {
    var email = $("form [name='" + Name + "']").val();
    var AtPos = email.indexOf("@");
    var lastAtPos = email.lastIndexOf("@");
    var DotPos = email.indexOf(".");
    var lastDotPos = email.lastIndexOf(".");
    if (email.length > 4) {
        if (AtPos > 1 && AtPos == lastAtPos) {

            if (DotPos > 0 && (lastDotPos - AtPos) > 3) {
                if (email.length - lastDotPos > 2) {
                    $("form [name='" + Name + "']").removeClass("is-invalid");
                } else {
                    $("form [name='" + Name + "']").addClass("is-invalid");
                    NullData++;
                }
            } else {
                $("form [name='" + Name + "']").addClass("is-invalid");
                NullData++;
            }
        } else {
            $("form [name='" + Name + "']").addClass("is-invalid");
            NullData++;
        }
    } else {
        $("form [name='" + Name + "']").addClass("is-invalid");
        NullData++;
    }
}

$("form [name]").blur(function () {
    if ($(this).val() != "") {
        $(this).removeClass("is-invalid");
    } else {
        $(this).addClass("is-invalid");

    }
})
$("form [name='email']").blur(function () {
    validEmail("email");
})


$("[data-edit]").click(function (event) {
    NullData = 0;
    ISNullData("pass");
    ISNullData("email");

    if (NullData == 0) {
        $('#loginUser')[0].submit(function (event) {
            event.preventDefault();
        });
    } else {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'خطا',
            text: 'لطفا اطلاعات را وارد کنید!'
        })
    }


})


$("[data-add]").click(function (event) {
    NullData = 0;
    ISNullData("name");
    ISNullData("phoneNumer");
    ISNullData("group");
    ISNullData("phoneType");

    if (NullData == 0) {
        $('#signUp')[0].submit(function (event) {
            event.preventDefault();
        });
    } else {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'خطا',
            text: 'لطفا اطلاعات را وارد کنید!'
        })
    }


})


$("[name='submit']").click(function (event) {
    NullData = 0;
    ISNullData("firstName");
    ISNullData("lastName");
    ISNullData("userName");
    ISNullData("pass");
    ISNullData("email");

    if (NullData == 0) {
        $('#myForm').submit(function (event) {
            event.preventDefault();
        });
    } else {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'خطا',
            text: 'لطفا اطلاعات را وارد کنید!'
        })
    }


})


$("[data-Insert]").click(function (event) {
    NullData = 0;
    ISNullData("name");

    if (NullData == 0) {
        $('#myForm').submit(function (event) {
            event.preventDefault();
        });
    } else {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'خطا',
            text: 'لطفا اطلاعات را وارد کنید!'
        })
    }


})


$("[data-editUser]").click(function (event) {
    NullData = 0;
    ISNullData("name");

    if (NullData == 0) {
        $('#myForm').submit(function (event) {
            event.preventDefault();
        });
    } else {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'خطا',
            text: 'اسم نمی تواند خالی باشد!'
        })
    }


})
