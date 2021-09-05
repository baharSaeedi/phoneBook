function editCon(element) {
    $("#editCon").val($(element).parent().parent().children("input").val());
    $("#editContactForm").submit();
}


function getMoreContact() {
    $.ajax({
        url: $("#getContactForm").attr("action"),
        type: $("#getContactForm").attr("method"),
        data: $("#getContactForm").serialize(),

        time:2000,
        success: function (response) {
            $(".contactsBox").html($(".contactsBox").html()+response).delay(3000).slideDown();
            if(response.match("مخاطب دیگری وجود ندارد"))
            {
                $("#moreCon").addClass("d-none");
            }
        },
        error: function () {

        },
        complete : function () {


            $("#getCon").val(parseInt($("#getCon").val())+5);


            $(".editCon").click(function () {
                editCon(this);
            });



            $(".delCon").click(function () {
                deleteCon(this);
            })


        }
    })
}


function deleteCon(element) {
    $("#delCon").val($(element).parent().parent().children("input").val());
    Swal.fire({
        title: " میخواهید مخاطب را پاک کنید؟",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#804df5",
        cancelButtonColor: "#9e9595",
        confirmButtonText: "بله",
        cancelmButtonText: "انصراف",
    }).then((result) => {
        if (result.isConfirmed) {

            window.location=location.pathname+$(element).attr("data-direct");

        }
    })
}



$(document).change(function () {


    $(".editCon").click(function () {
        editCon(this);
    });

    $(".delCon").click(function (event) {
        event.preventDefault();
        deleteCon(this);
    });


    $(".moreInfo").click(function () {

    })


    $(".fadeModal").click(function () {
        $('#MyModal').css('display','none');
        $('#MyModal').animate({'opacity':'0'},'slow');
    })


})



$(document).ready(function () {

    $("#moreCon").click(function () {
            getMoreContact()
    })


    $(".editCon").click(function () {
        editCon(this);
    });


    $(".delCon").click(function (event) {
        event.preventDefault();
        deleteCon(this);
    });

    $(".fadeModal").click(function () {
        $('#MyModal').css('display','none');
        $('#MyModal').animate({'opacity':'0'},'slow');
    })

    $(".morePhone").click(function () {
        $(this).parent().children(".inputs").html($(this).parent().children(".inputs").html()+"<div class='form-group d-flex justify-content-between text-right'>\n" +
            "                                                <p class='delInput icons mr-3 mt-4'  ><i class='fa fa-trash'></i></p>\n" +  " <input type='hidden' name='phoneId[]' value=''>"+ "<input list='phones' name='phoneType[]'  class='form-control text-center mr-2 w-25' value= 'تلفن همراه' >\n" +
            "  <datalist id='phones' >\n" +
            "    <option value= 'تلفن همراه'>\n" +
            "    <option value= 'خانه'>\n" +
            "    <option value= 'محل کار'>\n" +
            "    <option value= 'فکس'>\n" +
            "  </datalist>" +
            "                                                <input type='tel' name='phone[]' class='form-control text-right'>\n" +
            "                                                </div>");
    })











    $(".delInput").click(function () {
       $(this).parent().remove();
    })

})








$(document).on('click','.delInput',function () {
        $(this).parent().remove();
})


$(document).on('click','.moreInfo',function () {
    insert=$(this).parent().children().children(".form-group:last");
    inputs=$(this).parent().children("div").children(".form-group:first").clone(true);
    inputs.find("input").val("");
    $(inputs).insertAfter(insert);
})


$(document).on('click','.delInfo',function () {
    $(this).parent().children("[type='hidden']:eq(0)").attr("name", "deleteInfo[]");
    $(this).parent().children().addClass("d-none");
})


$('#pic').change(function() {
    var i = $(this).prev('label').clone();
    var file = $('#pic')[0].files[0];
    const imagen = URL.createObjectURL(file);
    $('#imageVisitor').removeClass("d-none");
    $('.fa-camera').addClass("d-none");
    $('#imageVisitor').attr('src', imagen);
});

